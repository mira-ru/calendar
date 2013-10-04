<?php
/**
 *
 */
Yii::setPathOfAlias('imgLoader', dirname(__FILE__));
Yii::import('imgLoader.models.*');
class ImageComponent extends CApplicationComponent
{
	const STATUS_PROGRESS = 1;
	const STATUS_DONE = 2;
	const STATUS_WRONG = 3;

	const DEFAULT_IMG = '/images/nophoto.svg';
	const DEFAULT_EXT = 'jpg';

	const PREFIX_ORIGIN = 'uploads/protected';
	const PREFIX_PREVIEW = 'uploads/public';

	private $_cache = array();

	/** @var array Список всех превью проекта (уникальные ключи используются для хранения и доступа */
	private static $preview = array(
		'crop_150' => array(150, 150, 'crop', 80),
		'crop_200' => array(200, 200, 'crop', 80),
	);

	/**
	 *
	 * @param $path полный путь до файла
	 * @param $name Название файла (с расширением)
	 * @throws CException
	 */
	public function putImage($path, $name, $desc='')
	{
		if ( !is_file($path) )
			throw new CException('No file to upload');

		$data = pathinfo($name);
		$name = isset($data['filename']) ? $data['filename'] : '';
		$ext = isset($data['extension']) ? $data['extension'] : 'JPEG';


		$image = new Image();
		$image->desc = $desc;
		$image->name =  $name;
		$image->ext = $ext;
		$image->status = self::STATUS_PROGRESS;
		$result = $image->save();
		if (!$result) { return null; }

		list($filePath, $fileName) = $this->generatePath($image->id);

		$filePath = self::PREFIX_ORIGIN .'/'.$filePath;
		if ( !file_exists($filePath))
			mkdir($filePath, 0700, true);

		$fullName = $filePath.'/'.$fileName.'.'.self::DEFAULT_EXT;
		$result = copy($path, $fullName);
		if (!$result) {
			$image->status = self::STATUS_WRONG;
			$image->save(false, array('status'));

			return null;
		}

		$image->status = self::STATUS_DONE;
		$image->save(false, array('status'));

		return $image->id;
	}

	/**
	 * Возвращает строку оригинального размера изображения (например 100x100)
	 */
	public function getOriginSize($id)
	{
		list($filePath, $fileName) = $this->generatePath($id);
		$fullName = self::PREFIX_ORIGIN.'/'.$filePath.'/'.$fileName.'.'.self::DEFAULT_EXT;

		$imgSizes = @getimagesize($fullName);
		if (!$imgSizes) {
			return '';
		}

		return $imgSizes['width'].'x'.$imgSizes['height'];
	}

	/**
	 * Генерация превью
	 * @param $id
	 * @param array $config
	 * @param bool $forceGenerate
	 * @return bool
	 */
	public function generatePreview($id, array $config, $forceGenerate=false)
	{
		return $this->makePreview($id, $config, $forceGenerate);
	}

	/**
	 * Отдает данные картинки, осуществляет кеширование в скрипте
	 * @param $id
	 * @return mixed
	 */
	private function getImageData($id, $useCache=true)
	{
		$id = intval($id);
		if ( isset($this->_cache[$id]) && $useCache ) {
			return $this->_cache[$id];
		}

		$image = Image::model()->findByPk($id);
		$this->_cache[$id] = $image;
		return $image;
	}

	public function getPreview($id, $configName, $default = self::DEFAULT_IMG)
	{
		$id = intval($id);
		// если пустой ключ - дефолтна пикча
		if (empty($id)) { return $default; }

		if ( !isset(self::$preview[$configName]) )
			throw new CException('Invalid config key', 500);

		list($filePath, $fileName) = $this->generatePath($id);

		$fullName = self::PREFIX_PREVIEW.'/'.$filePath.'/'.$configName.'_'.$fileName.'.'.self::DEFAULT_EXT;
		// файл есть
		if (file_exists($fileName)) { return '/'.$fullName; }

		$originName = self::PREFIX_ORIGIN.'/'.$filePath.'/'.$fileName.'.'.self::DEFAULT_EXT;
		// нет оригинала
		if (!file_exists($originName)) { return $default; }

		$this->makePreview($id, array($configName), false);

		return '/'.$fullName;
	}


	/**
	 * Удаление не используемого превью
	 * @param $id
	 * @param array $configName
	 * @return bool
	 * @throws CException
	 */
	public function deletePreview($id, $configName)
	{
		if ( !isset(self::$preview[$configName]) )
			throw new CException('Invalid config key', 500);

		list($filePath, $fileName) = $this->generatePath($id);

		$fullName = self::PREFIX_PREVIEW.'/'.$filePath.'/'.$configName.'_'.$fileName.'.'.self::DEFAULT_EXT;
		// файл есть
		if (file_exists($fullName)) {
			return @unlink($fullName);
		}
		return false;
	}

	/**
	 * Удаление оригинала и его превью
	 * @param $id
	 */
	public function deleteOrigin($id)
	{
		$image = $this->getImageData($id);
		if ($image === null) { return false; }

		list($filePath, $fileName) = $this->generatePath($id);

		$fullName = self::PREFIX_ORIGIN .'/'.$filePath.'/'.$fileName.'.'.self::DEFAULT_EXT;

		// файл есть
		if (file_exists($fullName)) {
			@unlink($fullName);
		}
		// удаляем превью
		foreach (self::$preview as $key => $item) {
			$this->deletePreview($id, $key);
		}
		$image->delete();
		return true;
	}

	/**
	 * Генерирует обобщенный путь для protected и public
	 * а также имя для сохранения
	 * @param $id
	 * @return array
	 */
	protected function generatePath($id)
	{
		$src = intval($id);
		$name = str_pad($src%1000, 3, '0', STR_PAD_LEFT);
		$src = intval($src/1000);

		$result = '';
		while ($src > 1000) {
			$tmp = $src%1000;
			$src = intval($src/1000);
			$result = '/'.$tmp.$result;
		}
		$result = $src.$result;

		$path = str_pad( $result, 11, '000/', STR_PAD_LEFT);

		return array($path, $name);
	}

	/**
	 * Обработчик создания превью.
	 *
	 * @param $id
	 * @param array $config
	 * @param bool $forceGenerate
	 * @return bool
	 * @throws CException
	 */
	private function makePreview($id, array $config, $forceGenerate=false)
	{
		Yii::import('imgLoader.ImageHandler.ImageHandler');
		list($filePath, $fileName) = $this->generatePath($id);
		$originName = self::PREFIX_ORIGIN.'/'.$filePath.'/'.$fileName.'.'.self::DEFAULT_EXT;

		if ( ! file_exists($originName)) {
			throw new Exception('Origin image not found', 500);
		}

		foreach ($config as $configName) {
			if (!isset(self::$preview[$configName]))
				throw new CException('Invalid image size');

			$folder = self::PREFIX_PREVIEW.'/'.$filePath;

			$previewName = $folder.'/'.$configName.'_'.$fileName.'.'.self::DEFAULT_EXT;


			if (file_exists($previewName) && !$forceGenerate) { continue; }

			if (!file_exists($folder)) { mkdir($folder, 0755, true); } // создание папок

			$imgConfig = self::$preview[$configName];

			// image processing
			$imageHandler = new ImageHandler($originName, ImageHandler::FORMAT_JPEG);
			$imageHandler->updateColorspace();

			$bestFit = isset($imgConfig[4]) ? $imgConfig[4] : true;
			$border = isset($imgConfig['border']) ? $imgConfig['border'] : false;

			if ($imgConfig[2] == 'crop') {
				$imageHandler->cropImage($imgConfig[0], $imgConfig[1], $imgConfig[3]);
			} else {

				if (isset($imgConfig['decrease']) && $imgConfig['decrease'] == true) {
					// Если фотка будет меньше указанного размера, то ресайзиться не будет
					$resizeType = ImageHandler::RESIZE_DECREASE;
				} else {
					// Ресайзим
					$resizeType = ImageHandler::RESIZE_BOTH;
				}
				$imageHandler->resizeImage($imgConfig[0], $imgConfig[1], $imgConfig[3], $resizeType, $bestFit);

				// Добавляем обрамление для фотографии
				if($border) {
					$imageHandler->addTransparentBorder($imgConfig[0], $imgConfig[1]);
				}
			}
			$imageHandler->saveImage($previewName);

		}
		return true;
	}

	/**
	 * Получение описания для картинки
	 * @param $id
	 * @return string
	 */
	public function getDesc($id, $useCache=true)
	{
		$image = $this->getImageData($id, $useCache);
		return ($image===null) ? '' : $image->desc;
	}

	/**
	 * Установка описания картинки
	 * @param $id
	 * @param $desc
	 * @return bool
	 */
	public function setDesc($id, $desc)
	{
		$image = $this->getImageData($id);
		if ($image===null) { return false; }
		$image->desc = $desc;

		return $image->save(true, array('desc'));
	}

	/**
	 * Получение ширины превью
	 * @param $id
	 * @param $configName
	 * @return null
	 * @throws CException
	 */
	public function getPreviewWidth($id, $configName, $default=null)
	{
		if (empty($id)) { return $default; }

		list($filePath, $fileName) = $this->generatePath($id);

		$fullName = self::PREFIX_PREVIEW.'/'.$filePath.'/'.$configName.'_'.$fileName.'.'.self::DEFAULT_EXT;

		$imgSizes = @getimagesize($fullName);
		if (!$imgSizes) { return $default; }

		return $imgSizes['width'];
	}

	/**
	 * Получение высоты превью
	 * @param $id
	 * @param $configName
	 * @return null
	 * @throws CException
	 */
	public function getPreviewHeight($id, $configName, $default=null)
	{
		if (empty($id)) { return $default; }

		list($filePath, $fileName) = $this->generatePath($id);

		$fullName = self::PREFIX_PREVIEW.'/'.$filePath.'/'.$configName.'_'.$fileName.'.'.self::DEFAULT_EXT;

		$imgSizes = @getimagesize($fullName);
		if (!$imgSizes) { return $default; }

		return $imgSizes['height'];
	}

}
