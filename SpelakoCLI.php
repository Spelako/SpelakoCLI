<?php
/*
 * Copyright (C) 2020-2022 Spelako Project
 * 
 * This file is part of SpelakoCLI.
 * Permission is granted to use, modify and/or distribute this program 
 * under the terms of the GNU Affero General Public License version 3.
 * You should have received a copy of the license along with this program.
 * If not, see <https://www.gnu.org/licenses/agpl-3.0.html>.
 * 
 * 此文件是 SpelakoCLI 的一部分.
 * 在 GNU Affero 通用公共许可证第三版的约束下,
 * 你有权使用, 修改, 复制和/或传播该软件.
 * 你理当随同本程序获得了此许可证的副本.
 * 如果没有, 请查阅 <https://www.gnu.org/licenses/agpl-3.0.html>.
 * 
 */

$cliargs = getopt('', ['core:', 'config:', 'yolo::']);

if(!(isset($cliargs['core']) && file_exists($cliargs['core']))) {
	exit('提供的 SpelakoCore 路径无效. 请使用命令行参数 "--core" 指向正确的 SpelakoCore.php.');
}

if(!(isset($cliargs['config']) && file_exists($cliargs['config']))) {
	exit('提供的配置文件路径无效. 请使用命令行参数 "--config" 指向正确的 config.json.');
}

require_once($cliargs['core']);
$core = new SpelakoCore(realpath($cliargs['config']));

if(isset($cliargs['yolo']) && $cliargs['yolo'] != false) {
	exit($core->execute($cliargs['yolo'], 'admin').PHP_EOL);
}

echo SpelakoUtils::buildString([
	'Copyright (C) 2020-2022 Spelako Project',
	'This program licensed under the GNU Affero General Public License version 3 (AGPLv3).'
]).PHP_EOL;

while(true) {
	echo '> /';
	$msg = rtrim(fgets(STDIN));
	$result = $core->execute('/'.$msg, 'admin');
	if($result) echo $result.PHP_EOL;
}
?>
