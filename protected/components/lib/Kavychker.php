<?php

class Kavychker
{
	private static $tags = array();
	private static $cnt;

	public static function baseFormat ($text, $purify=true)
	{
		$text = self::quotes ($text);
		$text = '<p>'.nl2br($text);
		$text = mb_ereg_replace("<br />(\s)*<br />(\s)*","\n\n<p>",$text,"UTF-8");
		$text = mb_ereg_replace("<p>(\s)*<p>","<p>",$text,"UTF-8");
		$text = str_replace ('<br />',"<br>", $text);

		if ($purify) {
			$text = self::purify($text);
		}

		return $text;
	}

	private static function purify($contents)
	{
		// удаляем потенциально опасные ссылки (оставляем только <a href=*> без параметров
		$contents = preg_replace ("/<a href=\"([^\"]*)\">/i", "[[¬$1¬]]", $contents);
		$contents = preg_replace ("/<a href='([^']*)'>/i", "[[¬$1¬]]", $contents);
		$contents = preg_replace ("/<a href=([^>]*)>/i", "[[¬$1¬]]", $contents);
		$contents = str_ireplace ("[[¬javascript:", "[[¬", $contents);

		// спасаем однословные тэги
		$contents = preg_replace ("/<([\/]{0,1})([\w]*)>/", "[¬$1$2¬]", $contents);
		$contents = strip_tags ($contents);

		// восстанавливаем однословные тэги
		$contents = str_ireplace ("¬]]", '">', $contents);
		$contents = str_ireplace ("[[¬", '<a href="', $contents);
		$contents = str_ireplace ("[¬", "<", $contents);
		$contents = str_ireplace ("¬]", ">", $contents);

		// оставляем только разрешенные тэги из однословных
		$contents = strip_tags($contents, '<br><p><b><i><u><a><s><blockquote><code><em><strong><dfn><code><samp><kbd><var><cite><sup><sub><li><ol><ul><strong><strike><small><smaller><large><larger>');
		return $contents;
	}

	public static function deformat ($text)
	{
		$text = str_replace ("&nbsp;"," ",$text);
		$text = str_replace ("<br />","",$text);
		$text = str_replace ("<br>","",$text);
		$text = str_replace ("<p>","",$text);
		return $text;
	}

	public static function _store ($tag)
	{
		self::$cnt++;
		self::$tags[self::$cnt] = $tag[0];
		return ("¬" . self::$cnt . "¬");
	}

	public static function _giveback ($num)
	{
		return self::$tags[$num[1]];
	}

	public static function quotes ($contents, $full = true)
	{
		// Version 3.1u
		// Copyright (c) Spectator.ru

		self::$cnt = 0;
		self::$tags = array();

		$contents = ' ' . $contents;

		$contents = str_replace ("&nbsp;", ' ', $contents);
		$contents = str_replace ("&laquo;", '«', $contents);
		$contents = str_replace ("&raquo;", '»', $contents);
		$contents = str_replace ("&bdquo;", '„', $contents);
		$contents = str_replace ("&ldquo;", '“', $contents);
		$contents = str_replace ("&#146;", '’', $contents);
		$contents = str_replace ("&#150;", '-', $contents);
		$contents = str_replace ("&mdash;", '—', $contents);

		if ($full)
		{
			$contents = preg_replace_callback ("/<script.*?<\/script>/uis", "self::_store", $contents);
			$contents = preg_replace_callback ("/<textarea.*?<\/textarea>/uis", "self::_store", $contents);
			$contents = preg_replace_callback ("/<code.*?<\/code>/uis", "self::_store", $contents);
			$contents = preg_replace_callback ("/<nof.*?<\/nof>/uis", "self::_store", $contents);
			$contents = preg_replace_callback('{<(?:[^\'"\>]+|".*?"|\'.*?\')+>}us', "self::_store", $contents);
		}

		$contents = preg_replace ("/([¬(\s\"])(\")([^\"]*)([^\s\"(])(\")/u", "\\1«\\3\\4»", $contents);

		// что, остались в тексте нераставленные кавычки? значит есть вложенные!
		if (mb_strrpos ($contents, '"'))
		{
			// расставляем оставшиеся кавычки (еще раз).
			$contents = preg_replace ("/([¬(\s\"])(\")([^\"]*)([^\s\"(])(\")/u", "\\1«\\3\\4»", $contents);
			// расставляем вложенные кавычки
			// видим: комбинация из идущих двух подряд открывающихся кавычек без закрывающей
			// значит, вторая кавычка - вложенная. меняем ее и идущую  после нее, на вложенную
			while (preg_match ("/(«)([^»]*)(«)([^»]*)(»)/u", $contents, $regs))
				$contents = str_replace ($regs[0], "{$regs[1]}{$regs[2]}„{$regs[4]}“", $contents);
		} ;

		$contents = str_replace ("'", '’', $contents);

		// тире
		$contents = str_replace (" - ", '&nbsp;— ', $contents);
		$contents = preg_replace ("/\s{1,}-\s{1,}/u", "&nbsp;— ", $contents);
		$contents = preg_replace ("/^- /u", "— ", $contents);
		$contents = preg_replace ("/¬- /u", "¬— ", $contents);

		if ($full)
		{
			$contents = preg_replace ("/([А-Яа-яA-Za-z]) (ли|ль|же|ж|бы|б)([^А-Яа-яA-Za-z])/u", '\\1&nbsp;\\2\\3', $contents);
			$contents = preg_replace ("/(\s)([А-Яа-я]{1})\s/u", '\\1\\2&nbsp;', $contents);

			while (preg_match ("/¬([^¬]*)¬/u", $contents))
			{
				$contents = preg_replace_callback ("/¬([^¬]*)¬/u", "self::_giveback", $contents);
			}
		}

		return trim ($contents);
	}
}
