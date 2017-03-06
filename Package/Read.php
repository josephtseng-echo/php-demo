<?php
/**
 * @author josephzeng

 */
namespace Package;

class Read {
	/**
	 * 
	 * @var unknown
	 */
	private $_packet_buffer;
	
	/**
	 * 
	 * @var integer
	 */
	private $_m_offset = 0;
	
	/**
	 * 
	 * @var integer
	 */
	protected $_cmd = 0;
	
	/**
	 * 
	 * @var integer
	 */
	protected $_version = 0;
	
	/**
	 * 
	 * @var integer
	 */
	protected $_reserved = 0;
	
	/**
	 * 
	 * @var array
	 */
	protected $_header_info = array();
	
	/**
	 * 
	 * @var integer
	 */
	private $_package_realsize = 0;
	
	/**
	 * 
	 * @var integer
	 */
	const HEARD_SIZE = 11;
	
	/**
	 * 
	 * @param unknown $buff
	 * @return boolean
	 */
	public function readPackageBuffer($buff) {
		$this->_packet_buffer ='';
		$this->_m_offset=0;
		$this->_package_realsize = strlen($buff);
		if ($this->_package_realsize < self::HEARD_SIZE) {
			return false;
		}
		$headerInfo = unpack("c2iden/scmd/cver/Nreserved/slen", substr($buff, 0, self::HEARD_SIZE));
		$this->_cmd = $headerInfo['cmd'];
		$this->_version = $headerInfo['ver'];
		$this->_vreserved = $headerInfo['reserved'];
		$this->_header_info = $headerInfo;
		$len = $headerInfo['len'];
		if (($len + self::HEARD_SIZE) !== $this->_package_realsize) {
			return false;
		}
		$this->_m_offset = self::HEARD_SIZE;
		$this->_packet_buffer = $buff;
		return true;
	}
	
	/**
	 * 
	 * @throws Exception
	 * @return mixed
	 */
	public function readByte() {
		if ($this->_package_realsize <= $this->_m_offset) {
			throw new Exception("read the overflow");
		}
		$temp = substr($this->_packet_buffer, $this->_m_offset, 1);
		if ($temp === false) {
			throw new Exception("read the overflow");
		}
		$value = unpack("C", $temp);
		$this->_m_offset+=1;
		return $value[1];
	}
	
	/**
	 * 
	 * @throws Exception
	 * @return mixed
	 */
	public function readShort() {
		if ($this->_package_realsize <= $this->_m_offset) {
			throw new Exception("read the overflow");
		}
		$temp = substr($this->_packet_buffer, $this->_m_offset, 2);
		if ($temp === false) {
			throw new Exception("read the overflow");
		}
		$value = unpack("s", $temp);
		$this->_m_offset+=2;
		return $value[1];
	}
	
	/**
	 * 
	 * @throws Exception
	 * @return mixed
	 */
	public function readInt() {
		if ($this->_package_realsize <= $this->_m_offset) {
			throw new Exception("read the overflow");
		}
		$temp = substr($this->_packet_buffer, $this->_m_offset, 4);
		if ($temp === false) {
			throw new Exception("read the overflow");
		}
		$value = unpack("i", $temp);
		$this->_m_offset+=4;
		return $value[1];
	}
	
	/**
	 * 
	 * @throws Exception
	 * @return number
	 */
	public function readUInt() {
		if ($this->_package_realsize <= $this->_m_offset) {
			throw new Exception("read the overflow");
		}
		$temp = substr($this->_packet_buffer, $this->_m_offset, 4);
		if ($temp === false) {
			throw new Exception("read the overflow");
		}
		list(, $var_unsigned) = unpack("L", $temp);
		$this->_m_offset+=4;
		return intval(sprintf("%u", $var_unsigned));
	}
	
	/**
	 * 
	 * @throws Exception
	 * @return string
	 */
	public function readString() {
		if ($this->_package_realsize <= $this->_m_offset) {
			throw new Exception("read the overflow");
		}
		$len = $this->readUInt();
		if ($len === false) {
			throw new Exception("read the overflow");
		}
		$realLen = $this->_package_realsize - $this->_m_offset;
		if ($realLen < $len) {
			throw new Exception("read the overflow");
		}
		$value = substr($this->_packet_buffer, $this->_m_offset, $len - 1);
		$this->_m_offset+=$len;
		return $value;
	}
	
	/**
	 * 
	 * @return number
	 */
	public function getLen(){
		return $this->_package_realsize;
	}
	
	/**
	 * 
	 */
	public function getHeaderInfo(){
		return $this->_header_info;
	}
	
	/**
	 * 
	 * @return number
	 */
	public function getCmd(){
		return $this->_cmd;
	}
	
	/**
	 * 
	 * @return number
	 */
	public function getVersion(){
		return $this->_version;
	}
	
	/**
	 * 
	 * @return number
	 */
	public function getReserved(){
		return $this->_reserved;
	}
}