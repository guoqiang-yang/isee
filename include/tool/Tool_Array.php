<?php
class Tool_Array
{
	/**
	 * 从对象(或数组)中提取指定字段的值
	 */
	public static function getFields($objs, $key)
	{
	    if (empty($objs)) return array();

		$ids = array();
		if (is_array($objs))
		{
			foreach($objs as $obj)
			{
				if (is_array($obj))
				{
					$ids[] = $obj[$key];
				}
				else if (is_object($obj))
				{
					$ids[] = $obj->$key;
				}
				else
				{
					$ids[] = $obj;
				}
			}
		}
		$ids = array_unique($ids);
		return $ids;
	}

	public static function list2Map(array $list, $key, $value_key=null)
	{
		if(empty($list))
		{
			return array();
		}

		$map = array();
		foreach($list as $item)
		{
			if ($value_key !== null)
			{
				$map[$item[$key]] = $item[$value_key];
			}
			else
			{
				$map[$item[$key]] = $item;
			}
		}
		return $map;
	}

	public static function filterEmpty(&$list)
	{
		if (!empty($list) && is_array($list))
		{
			foreach ($list as $idx => $item)
			{
				if (empty($item))
				{
					unset($list[$idx]);
				}
			}
		}
		return $list;
	}

	public static function sortByField(&$arr, $fieldName, $flag='desc')
	{
		$indexArr = array();
		foreach ($arr as $idx=>$item)
		{
			$indexArr[$idx] = $item[$fieldName];
		}

		if ('desc' == $flag)
		{
			arsort($indexArr);
		}
		else
		{
			asort($indexArr);
		}

		$result = array();
		foreach ($indexArr as $idx=>$field)
		{
			$result[$idx] = $arr[$idx];
		}
		$arr = $result;
		return $arr;
	}

	public static function checkCopyFields($srcArr, $fields)
	{
		$arr = array();
		foreach ($fields as $field)
		{
			$field = trim($field);
			assert( ! empty($field) );

			if (isset($srcArr[$field]))
			{
				$arr[$field] = $srcArr[$field];
			}
		}

		return $arr;
	}

	public static function copyFields($srcArr, array $fields, array $toFields=array())
	{
		assert(empty($toFields) || count($fields) == count($toFields));

		$arr = array();
		foreach ($fields as $idx => $field)
		{
			$field = trim($field);
			assert( ! empty($field) );

			if (empty($toFields))
			{
				$arr[$field] = $srcArr[$field];
			}
			else
			{
				$toField = $toFields[$idx];
				assert( ! empty($toField) );
				$arr[$toField] = $srcArr[$field];
			}
		}

		return $arr;
	}

    /**
     * 例如： [{uid,...},...] 根据uid补充为 [{uid,uname,umobile,...},...]
     *
     * @param array $toArr
     * @param $idxField
     * @param array $srcArr
     * @param array $srcFields
     */
    public static function copyFieldsTo(array &$toArr, $idxField, array $srcArr, array $srcFields)
    {
        foreach ($toArr as &$_item)
        {
            $_idx = $_item[$idxField];
            foreach ($srcFields as $_field)
            {
                $_item[$_field] = $srcArr[$_idx][$_field];
            }
        }
    }

    //假设$srcArr的key为主键
    public static function appendObjTo(array &$toArr, $toField, $toObjField, array $srcArr)
    {
        if (empty($srcArr) || empty($toArr)) return;

        foreach ($toArr as &$_item)
        {
            $_id = $_item[$toObjField];
            if (!empty($srcArr[$_id])) {
                $_item[$toField] = $srcArr[$_id];
            }else{
                $_item[$toField] = array();
            }
        }
    }

    public static function mergeFields($srcArr, &$toArr, $fields)
	{
		foreach ($fields as $field)
		{
			$field = trim($field);
			assert( ! empty($field) );

			if (isset($srcArr[$field]))
			{
				$toArr[$field] = $srcArr[$field];
			}else
			{
				$toArr[$field] = '';
			}
		}

		return $toArr;
	}

	public static function rand($source,$count = 1)
	{
		if(!is_array($source) || $count <= 0)
		{
			return array();
		}
		if($count == 1)
		{
			$keys = array(array_rand($source,$count));
		}
		else
		{
			$keys = array_rand($source,$count);
		}

		$list = array();
		foreach($keys as $key)
		{
			$list[$key] = $source[$key];
		}
		if($count == 1 && !empty($list))
		{
			return array_shift($list);
		}
		return $list;
	}

	public static function where($collection,$where)
	{
		if(!is_array($where) || empty($where))
		{
			return $collection;
		}
		$list = array();
		foreach($collection as $idx=>$item)
		{
			$hit = true;
			foreach($where as $key=>$val)
			{
				if(isset($item[$key]) && $item[$key] != $val)
				{
					$hit = false;
					break;
				}
			}
			if($hit)
			{
				$list[$idx] = $item;
			}
		}
		return $list;
	}

	//为了防止汉字被解析成unicode编码，5.4以上可以用参数实现
	public static function jsonEncode($params)
	{
		$params = self::_url_encode($params);
		$params = json_encode($params);
		$params = urldecode($params);

		return $params;
	}

	public static function sumFields($arr, $filed)
	{
		if (empty($arr))
		{
			return 0;
		}

		$sum = 0;
		foreach ($arr as $item)
		{
			$sum += $item[$filed];
		}

		return $sum;
	}

	private static function _url_encode($arr)
	{
	    $res = array();

		foreach ($arr as $k => $v)
		{
            $k = urlencode($k);
			if (is_array($v))
			{
                $res[$k] = self::_url_encode($v);
			}
			else
			{
                $res[$k] = urlencode($v);
			}
		}

		return $res;
	}
	
	public static function a2s($arr, $d1=',', $d2=':')
	{
        $_resTmp = array();
        foreach ($arr as $_key => $_score) {
            $_resTmp[] = $_key . $d2 . $_score;
        }
        return implode($d1, $_resTmp);
	}

    public static function s2a($str, $d1=',', $d2=':')
    {
        $str = trim($str);
        if (empty($str)) return array();

        $result = array();
        foreach (explode($d1, $str) as $_chunk) {
            list($_key, $_score) = explode($d2, $_chunk);
            $result[$_key] = $_score;
        }
        return $result;
    }

    public static function f2a($fileName, $sp1="\n", $sp2="\t")
    {
        if (!file_exists($fileName)) {
            return array();
        }
        $fileContents = array();
        $tmpFileContents = explode($sp1, file_get_contents($fileName));
        foreach($tmpFileContents as $contentItem) {
            $fileContents[] = explode($sp2, $contentItem);
        }

        return $fileContents;
    }

    /**
     * 按权重，随机获取num个元素
     * @param $arr  格式例如 array('小米'=>21, '黑米'=>1);
     * @param $num
     * @return mixed 格式例如 array('小米'=>21)
     */
    public static function randByWeight($arr, $num)
    {
        //辅助数组
        $_keyTables = array();
        foreach ($arr as $_key => $_score)
        {
            $_score = intval($_score);
            $_num = empty($_score) ? 1:$_score;
            for ($i=0; $i<$_num; $i++) {
                $_keyTables[]=$_key;
            }
        }

        //根据权重随机获取数据
        $result = array();
        for ($i=0; $i<$num; $i++)
        {
            $_keyTablesNum = count($_keyTables);
            $_idx = rand(0, $_keyTablesNum-1);
            $_key = $_keyTables[$_idx];
            $result[$_key] = $arr[$_key];

            foreach($_keyTables as $_i=>$_v) {
                if ($_v == $_key) unset($_keyTables[$_i]);
            }
            $_keyTables = array_values($_keyTables);
            if (empty($_keyTables)) break;
        }

        arsort($result);
        return $result;
    }

    public static function arrayIncrement(&$arr, $arrKey, $incNum)
    {
        if (!empty($arr[$arrKey])) {
            $arr[$arrKey] += $incNum;
        } else {
            $arr[$arrKey] = $incNum;
        }
    }

}
