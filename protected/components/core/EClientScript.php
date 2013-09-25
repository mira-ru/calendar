<?php
/**
 * Class EClientScript
 * Кастомизация CClientScript
 */
class EClientScript extends CClientScript
{
	public $timeFile = '';

	private $_suffix = '';

	public function init()
	{

		parent::init();

		if (empty($this->timeFile)) {
			return;
		}

		if (file_exists($this->timeFile)) {
			$this->_suffix = file_get_contents($this->timeFile);
			return;
		}
		$this->_suffix = time();
		file_put_contents($this->timeFile, $this->_suffix);
	}

	/**
	 * CSS file с обновлением после деплоя
	 * @param string $url URL of the CSS file
	 * @param string $media media that the CSS file should be applied to. If empty, it means all media types.
	 * @return CClientScript the CClientScript object itself (to support method chaining, available since version 1.1.5).
	 */
	public function releaseCssFile($url,$media='')
	{
		$url .= '?'.$this->_suffix;
		return $this->registerCssFile($url, $media);
	}

	/**
	 * Registers a javascript file with update after deploy.
	 * @param string $url URL of the javascript file
	 * @param integer $position the position of the JavaScript code. Valid values include the following:
	 * <ul>
	 * <li>CClientScript::POS_HEAD : the script is inserted in the head section right before the title element.</li>
	 * <li>CClientScript::POS_BEGIN : the script is inserted at the beginning of the body section.</li>
	 * <li>CClientScript::POS_END : the script is inserted at the end of the body section.</li>
	 * </ul>
	 * @param array $htmlOptions additional HTML attributes
	 * @return CClientScript the CClientScript object itself (to support method chaining, available since version 1.1.5).
	 */
	public function releaseScriptFile($url,$position=null,array $htmlOptions=array())
	{
		$url .= '?'.$this->_suffix;
		return $this->registerScriptFile($url, $position, $htmlOptions);
	}

	/**
	 * Получение текущей версии релиза
	 * @return string
	 */
	public function getVersion()
	{
		return $this->_suffix;
	}
}