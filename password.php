<?php
// 选择时长模式: 1,2,3,4,5
$index = intval($_GET['i']) ? intval($_GET['i']) : 1;

// 密钥 eg.$key = 'RUIOO';
$key = 'THLM10';

// 设备编号 eg.$devicesn = '18B1P00001';
$devicesn = '13P00ZA01924';

// 序号, 默认只生成20个
$num = 20;

// 生成随机密码组
$array = createPwd($key, $devicesn, $num);

// 生成随机密码
$random_password = createRandPwd($array, $index, $num);

echo '随机密码：' . $random_password;

// var_dump($array);

/**
 * 生成随机密码组
 * @param  string $key 密钥
 * @param  string $devicesn 设备号
 * @param  integer $num 序号
 * @return array 密码组
 */
function createPwd($key, $devicesn, $num = 20) {

	$array = array();

	// $i: 当前序号, 一共20个序号
	for ($i = 0; $i < $num; $i++) {
		$num_len = strlen($num);

		$index = $i + 1;
		$index_i = '';
		if (strlen($index) < $num_len) {
			$index_j = '';
			for ($j = 0; $j < $num_len - strlen($index); $j++) {
				$index_j .= '0';
			}
			$index_i .= $index_j . $index;
		} else {
			$index_i = $index;
		}

		// hexdec(hex_string) 函数把十六进制转换为十进制。
		$hex = hexdec('0x' . substr(md5($key . $devicesn . $index_i), -3));

		// 左，右移动运算符 eg.(x << y): 把x转成二进制, 往左移动三位, 再转回十进制
		$second = (($hex >> 9) & 0x7) % 5 + 1;
		$third = (($hex >> 6) & 0x7) % 5 + 1;
		$fourth = (($hex >> 3) & 0x7) % 5 + 1;
		$fifth = ($hex & 0x7) % 5 + 1;

		$array[$i] = $second . $third . $fourth . $fifth;
	}

	return $array;
}

/**
 * 生成随机密码
 * @param  array 随机密码组
 * @param  integer 时长模式
 * @param  integer 序号
 * @param  array 自定义规则
 * @return string 随机密码
 */
function createRandPwd($array, $index, $num = 20, $rules = []) {
	if (!$rules) {
		$rules = ['11111', '22222', '33333', '44444', '55555'];
	}

	do {
		$random_num = mt_rand(1, $num);
		$random_password = $index . $array[$random_num];
	} while (in_array($random_password, $rules));

	return $random_password;
}
