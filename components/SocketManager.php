<?php


class SocketManager {

    /**
     * 发送公钥
     */
    private $_secret_encode_key = '111115555aaa33336666';

    /**
     * 接收私钥
     */
    private $_secret_decode_key = '111115555aaa33336666';


    /**
     * socket句柄
     */
    private $_socket = null;

    /**
     * 错误信息代码
     */
    private $_error = array(
         0 => '执行成功',
         1 => '账号登录状态为不在线',
        -1 => '执行失败',
    );
    /**
     * 账号封禁
     * @var unknown_type
     */
    const COMMAND_DENY_LOGIN = 1080;

    public function __destruct() {
        $this->close();
    }

    /**
     * 打开Socket通信端口
     */
    public function open() {
        if ($this->_socket === null) {
            try {
                $this->_socket = fsockopen($this->_api_host, $this->_api_port);
            } catch (Exception $ex) {                
                $this->_socket = null;
            }
        }
        return $this->_socket;
    }

    /**
     * 关闭Socket通信端口
     */
    public function close() {
        try {
            @fclose($this->_socket);
        } catch (Exception $ex) {
            
        }
        $this->_socket = null;
    }

    /**
     * 快捷方法：发送一次GM命令
     * @param int $command 命令ID
     * @param array $params 命令参数
     * @param int $serial 指定序号，命令执行完毕后会返回本序号
     * @param boolean $encode 是否加密后传输
     * @return array 请取$ret['code']
     */
    public function execute($command, $params, $serial = 0, $encode = true) {
        $this->send($command, $params, $serial, $encode);
        $result = $this->read();
        $this->close();
        return $result;
    }

    /**
     * 自动打开端口发送数据，但不会自动关闭端口，适合批量发送
     * @param int $command 命令ID
     * @param array $params 命令参数
     * @param int $serial 指定序号，命令执行完毕后会返回本序号
     * @param boolean $encode 是否加密后传输
     * @return boolean
     */
    public function send($command, $params, $serial = 0, $encode = true) {
        $body = $this->enbody($command, $params, $serial, $encode);
        //打开端口
        if ($this->open()) {
            //发送数据
            $writebyte = fwrite($this->_socket, $body, strlen($body));
            usleep(100 * 1000); //延迟100毫秒才能再次发送
            return $writebyte;
        } else {
            return false;
        }
    }

    /**
     * 读取返回数据，支持长连接，同时处理多个返回结果
     * @return array
     */
    public function read() {
        if (empty($this->_socket)) {
            return array('code' => -999, 'msg' => '端口未打开，请检查服务器是否启动');
        }
        $result = array();
        while (!feof($this->_socket)) {
            //先读取包长
            $length = fread($this->_socket, 4);
            $bodyLength = unpack('L', $length);
            $remain = $bodyLength[1] - 4;
            //读取剩下的数据（防半包）
            $bodyContent = '';
            while ($remain > 0) {
                $content = fread($this->_socket, $remain);
                $bodyContent .=$content;
                $remain = $remain - strlen($content);
            }
            //压入结果集
            $result[] = $this->debody($bodyContent);
            //有时流的终点不是用eof标记，也不是固定的标志
            $stream_meta_data = stream_get_meta_data($this->_socket);
            if ($stream_meta_data['unread_bytes'] <= 0) {
                break;
            }
        }
        if (count($result) == 1) {
            return $result[0];
        } else {
            return $result;
        }
    }

    /**
     * 打包协议
     * @param int $command 命令ID
     * @param array $params 命令参数
     * @param int $serial 指定序号，命令执行完毕后会返回本序号
     * @param boolean $encode 是否加密后传输
     * @return string
     */
    private function enbody($command, $params, $serial = 0, $encode = true) {
        if (!is_array($params)) {
            $params = array();
        }
        if (empty($serial)) {
            $serial = time() . rand(1, 1000);
        }
        //构造BODY
        $body = array();
        $body['command'] = intval($command);
        $body['serial'] = floatval($serial);
        $body['data'] = $params;
        if ($encode) {//加密
            $content = $this->encode(json_encode($body));
            $content = pack('S', 1767) . pack('S', strlen($content)) . $content;    //1767
            $content = pack('L', strlen($content) + 4) . $content;
        } else {//不加密
            $content = json_encode($body);
            $content = pack('S', 889) . pack('S', strlen($content)) . $content;    //889
            $content = pack('L', strlen($content) + 4) . $content;
        }
        return $content;
    }

    /**
     * 解包协议
     * @return array 数组中固定包含'code','msg','data'三个元素
     */
    private function debody($bodyContent) {
        $encode_data = unpack('S', substr($bodyContent, 0, 2));
        $encode = $encode_data[1];
        $length_data = unpack('S', substr($bodyContent, 2, 2));
        $length = $length_data[1];
        $body = substr($bodyContent, 4, $length);
        
        if ($encode == 1690) {//需要解密    //1690
            $body = $this->decode($body);
        } elseif ($encode == 1056) {    //1056
            //不需要解密
        }
        $result = json_decode($body, true);
        $retCode = '';
        if (!isset($result['code'])) {
            //预防未返回错误码
            $result['code'] = -888;
        } elseif (!isset($this->_error[$result['code']])) {
            $retCode = "(服务器返回原始code: {$result['code']})";
            //预防返回了未知的错误码
            $result['code'] = -777;
        }
        if (!isset($result['data'])) {
            //预防未返回data数据
            $result['data'] = array();
        }
        $result['msg'] = $this->_error[$result['code']] . $retCode;
        return $result;
    }

    /**
     * AES内容加密
     * @param string $data
     * @return string
     */
    private function encode($data) {
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $this->_secret_encode_key, $iv);
        $encrypted = mcrypt_generic($td, $data);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $result = $iv . $encrypted;
        $result = base64_encode($result);
        return $result;
    }

    /**
     * AES内容解密
     * @param string $data
     * @return string
     */
    private function decode($data) {
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $content = substr($data, 16);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        mcrypt_generic_init($td, $this->_secret_decode_key, $iv);
        $result = mdecrypt_generic($td, $content);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $result;
    }

}
