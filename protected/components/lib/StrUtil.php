<?php
/**
 * Набор утилит для обработки строк
 */
class StrUtil
{
	/**
	 * @brief Method for amputations of the text and add dots to the amputated member.
	 * @param string $text (Source text)
	 * @param int $limit (Maximum number of characters)
	 * @param string $postfix
	 * @param string $charset
	 * @param bool $returnLimb - вернуть обрезок по буквам (не по словам)
	 * @return string
	 */
	public static function getLimb($text='', $limit=20, $postfix='...', $charset='utf-8', $returnLimb = false)
	{
		if (mb_strlen($text, $charset) > $limit) {

			$limb = mb_substr($text, 0, $limit, $charset);

			if($returnLimb)
				return $limb . $postfix;

			return mb_substr($limb, 0, mb_strrpos($limb, " ", null, $charset), $charset) . $postfix;
		} else {
			return $text;
		}
	}

	/**
	 * Convert array to csv string
	 * @param $data
	 * @return string
	 */
	public static function arrayToCsv($data)
	{
		$result = '';
		foreach ($data as $row) {
			$cnt = 0;
			if (!is_array($row)) {
				$row = array($row);
			}
			foreach($row as $cell) {
				if ($cnt > 0) {
					$result .= ';';
				} else {
					$cnt++;
				}
				$result .= '"'.$cell.'"';
			}
			$result .= "\r\n";
		}
		$result = iconv('UTF-8', 'windows-1251', $result);
		return $result;
	}

	/**
	 *
	 */
	public static function videoUrlConvert($url)
	{
		$url = trim($url, ' /');
		$parse = parse_url ($url);
		$params = isset($parse['query']) ? $parse['query'] : array();
		if (!isset($parse['host'])) {
			return false;
		}

		if ($parse['host']=="youtu.be")
		{
			if (!isset($parse['path']))
				return false;

			$v = $parse['path'];

			$video =
			    '<iframe title="YouTube video player" width="480" height="390" src="http://www.youtube.com/embed/'.$v.'?wmode=opaque" frameborder="0" allowfullscreen></iframe>';

			return $video;
		}

		if ($parse['host']=="www.youtube.com" || $parse['host']=="youtube.com")
		{
			if (!isset($params['v']))
				return false;

			$v = $params['v'];

			$video =
			    '<iframe title="YouTube video player" width="480" height="390" src="http://www.youtube.com/embed/'.$v.'?wmode=opaque" frameborder="0" allowfullscreen></iframe>';

			return $video;
		}

		if ($parse['host']=="vimeo.com")
		{
			if (!isset($parse['path']))
				return false;

			$v = $parse['path'];

			$video =
			    '<iframe src="http://player.vimeo.com/video'.$v.'" width="480" height="270" frameborder="0"></iframe>';

			return $video;
		}

//		if ($parse['host']=="vk.com")
//		{
			// ВК не было, а надо
			// http://vk.com/video-24154167_163272489
			// <iframe src="http://vk.com/video_ext.php?oid=-24154167&id=163272489&hash=5533fa315ea273f0" width="607" height="360" frameborder="0"></iframe>
//		}

		if ($parse['host'] == 'fotki.yandex.ru') {

			if ( isset($parse['path']) && preg_match('@^users/([-\w]+)/album/([\d]+)/slideshow$@u', trim($parse['path'], ' /'), $pathArray)) {
				$user = $pathArray[1];
				$id = $pathArray[2];
			} else {
				return false;
			}

			$video = '<object width="500" height="375">'
			    	.'<param name="flashvars" value="author='.$user.'&mode=album&effects=1&time=5&id='.$id.'" />'
			    	.'<param name="bgcolor" value="#000000" />'
			    	.'<param name="movie" value="http://fotki.yandex.ru/swf/slideshow" />'
			    	.'<param name="allowFullScreen" value="true" />'
				.'<embed src="http://fotki.yandex.ru/swf/slideshow" allowFullScreen="true" width="500" height="375" '
					.'flashvars="author='.$user.'&mode=album&effects=1&time=5&id='.$id.'" type="application/x-shockwave-flash" bgcolor="#000000" />'
			    	.'</object>';
			return $video;
		}

		return false;
	}
}

