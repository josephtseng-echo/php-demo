<?php
/**
 * @author josephzeng

 */
namespace Package;

class Write {
	/**
	 * 
	 * @var integer
	 */
	protected $_len = 0;
	
	/**
	 * 
	 * @var unknown
	 */
	private $_packet_buffer;
	
	/**
	 * 
	 * @var string
	 */
	protected $_packet_prefix = 'XZ';
	
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
	 * @param unknown $cmd
	 * @param number $version
	 * @param number $reserved
	 * @param string $packet_prefix
	 */
	public function writeBegin($cmd, $version = 1, $reserved = 0, $packet_prefix = 'XX') {
		$this->_cmd = $cmd;
		$this->_packet_buffer = "";
		$this->_len = 0;
		$this->_reserved = $reserved;
		$this->_version = $version;
		$this->_packet_prefix = $packet_prefix;
	}
	
	/**
	 * 
	 * @param unknown $value
	 */
	public function writeString($value) {
		$len = strlen($value);
		$this->_packet_buffer.=pack("L", $len + 1);
		if ($len > 0) {
			$this->_packet_buffer.=$value;
		}
		$this->_packet_buffer.=pack("C", 0);
		$this->_len+=$len + 1 + 4;
	}
	
	/**
	 * 
	 * @param unknown $value
	 */
	public function writeInt($value) {
		$this->_packet_buffer.=pack("i", $value);
		$this->_len+=4;
	}
	
	/**
	 * 
	 * @param unknown $value
	 */
	public function writeUInt($value) {
		$this->_packet_buffer.=pack("L", $value);
		$this->_len+=4;
	}
	
	/**
	 * 
	 * @param unknown $value
	 */
	public function writeByte($value) {
		$this->_packet_buffer.=pack("C", $value);
		$this->_len+=1;
	}
	
	/**
	 * 
	 * @param unknown $value
	 */
	public function writeShort($value) {
		$this->_packet_buffer.=pack("s", $value);
		$this->_len+=2;
	}
	
	/**
	 * 
	 * @throws Exception
	 * @return \Package\unknown
	 */
	public function writeEnd() {
		if ($this->_len > 65500) {
			throw new Exception("packets can't exceed 65500 bytes");
		}
		$this->_packet_buffer = $this->_packet_prefix . pack('s', $this->_cmd) . pack('C', $this->_version) . pack('N', $this->_reserved) . pack('s', $this->_len) . $this->_packet_buffer;
		return $this->_packet_buffer;
	}
	
	/**
	 * 
	 * @return \Package\unknown
	 */
	public function getPacketBuffer() {
		return $this->_packet_buffer;
	}
}