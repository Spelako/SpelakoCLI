<?php
/*
 * Copyright (C) 2020-2022 Spelako Project
 * 
 * Permission is granted to use, modify and/or distribute this program under the terms of the GNU Affero General Public License version 3 (AGPLv3).
 * You should have received a copy of the license along with this program. If not, see <https://www.gnu.org/licenses/agpl-3.0.html>.
 * 
 * 在 GNU 通用公共许可证第三版 (AGPLv3) 的约束下, 你有权使用, 修改, 复制和/或传播该软件.
 * 你理当随同本程序获得了此许可证的副本. 如果没有, 请查阅 <https://www.gnu.org/licenses/agpl-3.0.html>.
 * 
 */

$cliargs = getopt('', ['core:', 'yolo::']);

if(!(isset($cliargs['core']) && file_exists($cliargs['core']))) {
	echo '提供的 SpelakoCore 路径无效. 请使用命令行参数 "--core" 指向正确的 SpelakoCore.php.';
	die();
}

function onException(string $path, int $line, string $str, int $no) {
	$path = str_replace(getcwd(), '', $path); 
	$str = str_replace(getcwd(), '', $str); 
	echo SpelakoUtils::buildString([
		'Spelako 在运行时出现了一个致命的错误!',
		'位置: '.$path.' - 第 '.$line.' 行',
		'内容: '.$str.' ('.$no.')'
	], eol: true);
}
set_error_handler(function($errno, $errstr, $errfile, $errline) {
	onException($errfile, $errline, $errstr, $errno);
}, E_ALL | E_STRICT);
set_exception_handler(function($e) {
	onException($e->getFile(), $e->getLine(), $e->getMessage(), $e->getCode());
});

require_once($cliargs['core']);
$core = new SpelakoCore();

foreach(glob($core->getcwd().'/commands/*.php') as $file) {
	require_once($file);
	$classname = basename($file, '.php');
	$core->loadCommand($classname);
}

if(isset($cliargs['yolo']) && $cliargs['yolo'] != false) {
	echo $core->execute($cliargs['yolo'], 'admin').PHP_EOL;
}
else {
	cli_set_process_title('Spelako CLI');
	echo SpelakoUtils::buildString([
		'Copyright (C) 2020-2022 Spelako Project',
		'This program licensed under the GNU Affero General Public License version 3 (AGPLv3).'
	], eol: true);

	while(true) {
		echo '> /';
		$msg = rtrim(fgets(STDIN));
		$result = $core->execute('/'.$msg, 'admin');
		if($result) echo $result.PHP_EOL;
	}
}
?>
