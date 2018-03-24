<?php

/**
 * 格式化参数
 * @author gongzhiling
 *
 */
class FormatParam {
	
	/**
	 * 格式化传过来的参数
	 * @param string $keyword
	 * @return NULL|string
	 */
	static public function formatGetParams($keyword) {
		if (empty ( $keyword ) || is_array ( $keyword )) {
			return null;
		}
		// 去除html标签
		$keyword = strip_tags ( $keyword );
		//把\x换为 %
		if (strstr ( $keyword, '\x' )) {
			$keyword = str_replace ( '\x', '%', $keyword );
		}
		// 处理IE乱码
		$keyword = urldecode ( $keyword );
		if (strstr ( $keyword, '%' )) {
			$keyword = str_replace ( '%', '', $keyword );
		}
		//把'替换
		if (strstr ( $keyword, "'" )) {
			$keyword = str_replace ( "'", '', $keyword );
		}
		//把"替换
		if (strstr ( $keyword, '"' )) {
			$keyword = str_replace ( '"', '', $keyword );
		}
		//把=替换
		if (strstr ( $keyword, '=' )) {
			$keyword = str_replace ( '=', '', $keyword );
		}
		//把<替换
		if (strstr ( $keyword, '<' )) {
			$keyword = str_replace ( '<', '', $keyword );
		}
		//把>替换
		if (strstr ( $keyword, '>' )) {
			$keyword = str_replace ( '>', '', $keyword );
		}
		//转码
		$keyword = self::utf8RawUrlDecode ($keyword);
		//去除\
		$keyword = stripslashes ( $keyword );
		//对特殊字符进行过滤
		$keyword = htmlspecialchars ( $keyword );
		return $keyword;
	}
	
	/**
	 * @Url中文转码（编解码）函数
	 */
	static public function utf8RawUrlDecode ($source)
	{
		$decodedStr = "";
		$pos = 0;
		$len = strlen ($source);
		while ($pos < $len) {
			$charAt = substr ($source, $pos, 1);
			if ($charAt == '%') {
				$pos++;
				$charAt = substr ($source, $pos, 1);
				if ($charAt == 'u') {
					// we got a unicode character
					$pos++;
					$unicodeHexVal = substr ($source, $pos, 4);
					$unicode = hexdec ($unicodeHexVal);
					$entity = "&#". $unicode . ';';
					$decodedStr .= utf8_encode ($entity);
					$pos += 4;
				}
				else {
					// we have an escaped ascii character
					$hexVal = substr ($source, $pos, 2);
					$decodedStr .= chr (hexdec ($hexVal));
					$pos += 2;
				}
			} else {
				$decodedStr .= $charAt;
				$pos++;
			}
		}
		return $decodedStr;
	}
}