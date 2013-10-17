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
}