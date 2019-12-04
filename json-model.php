<?php
/**
 * @example
 * $jsonDb = new JsonModel(APP_ROOT . DS . 'res');
 * 
 * // Get "users"
 * $jsonDb->select('*')
 *   ->from('users.json')
 *   ->get();
 * 
 * // Chaining
 * $jsonDb->select('name, state')
 *   ->from('users.json')
 *   ->where(['name' => 'Thomas'])
 *   ->orderBy('age', JSONDB::ASC)
 *   ->get();
 */
class JsonModel {
  public $filePath = null;
  public $content = [];
  
  private $where = null;
  private $select = null;
  private $merge = null;
  private $update = null;

	private $delete = false;
	private $lastIndices = [];
	private $orderBy = [];
	protected $dir = null;
  private $jsonOps = [];
  
	const ASC = 1;
  const DESC = 0;
  
  /**
   * Constructor
   * @param type $dir
   * @param type $jsonEncodeOpts Optional. Default JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT
   */
	public function __construct($dir, $jsonEncodeOpts = JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) {
		$this->dir = $dir;
		$this->jsonOps['encode'] = $jsonEncodeOpts;
  }
  
  /**
   * Checks and validates if JSON file exists
   * @return bool
   */
	private function checkFile() {
		// Checks if JSON file exists, if not create
		if(!file_exists($this->filePath)) {
			$this->commit();
    }
    
		// Read content of JSON file
		$content = file_get_contents($this->filePath);
    $content = json_decode($content);
    
		// Check if array
		if(!is_array($content) && is_object($content)) {
			throw new \Exception('JSON must be an array');
			return false;
    }	elseif(!is_array($content) && !is_object($content)) {
      // An invalid jSON file
			throw new \Exception('Invalid JSON');
			return false;
		} else {
      // File is good
      return true;
    }
  }
  
  /**
   * Explodes the selected columns into array
   * @param type $args Optional. Default *
   * @return type object
   */
	public function select($args = '*') {
		// Explode to array
		$this->select = explode(',', $args);
		// Remove whitespaces
		$this->select = array_map('trim', $this->select);
		// Remove empty values
		$this->select = array_filter($this->select);
		return $this;
  }

  /**
   * Loads the jSON file
   * @param type $fileName. Accepts file path to jSON file
   * @return type object
   */
	public function from($fileName) {
    $this->filePath = $this->dir . DS . $fileName;

		// Reset where
		$this->where([]);
    $this->content = '';

		// Reset order by
		$this->orderBy = [];
		if($this->checkFile()) {
			$this->content = (array)json_decode(file_get_contents($this->filePath));
    }

		return $this;
  }

  // 
	public function where(array $columns, $merge = 'OR' ) {
		$this->where = $columns;
		$this->merge = $merge;
		return $this;
  }
  
  // 
	public function delete() {
		$this->delete = true;
		return $this;
  }
  
  // 
	public function update( array $columns ) {
		$this->update = $columns;
		return $this;
  }
  
	/**
	 * Inserts data into json file
	 * @param string $file json filename without extension
	 * @param array $values Array of columns as keys and values
	 * @return array $lastIndices Array of last index inserted
	 */
	public function insert($file, array $values) {
    $this->from($file);
    
		if(!empty($this->content[0])) {
      $nulls = array_diff_key( (array)$this->content[0], $values );
			if($nulls) {
				$nulls = array_map(function() {
					return '';
				}, $nulls);
				$values = array_merge($values, $nulls);
			}
    }
    
		if(!empty($this->content) && array_diff_key($values, (array)$this->content[0])) {
			throw new Exception('Columns must match as of the first row');
    } else {
			$this->content[] = (object)$values;
			$this->lastIndices = [ (count($this->content) - 1) ];
			$this->commit();
    }

		return $this->lastIndices;
  }

  // 
	public function commit() {
		$f = fopen($this->filePath, 'w+');
    fwrite($f, (!$this->content
      ? '[]'
      : json_encode($this->content, $this->jsonOps['encode'])
    ));
		fclose($f);
  }

  // 
	private function _update() {
		if( !empty( $this->lastIndices ) && !empty( $this->where ) ) {
			foreach( $this->content as $i => $v ) {
				if( in_array( $i, $this->lastIndices ) ) {
					$content = ( array ) $this->content[ $i ];
					if( !array_diff_key( $this->update, $content ) ) {
						$this->content[ $i ] = ( object ) array_merge( $content, $this->update );
					}	else {
            throw new Exception('Update method has an off key');
          }
				}	else {
          continue;
        }
			}
		}	elseif( !empty($this->where) && empty($this->lastIndices) ) {
			null;
		}	else {
			foreach($this->content as $i => $v) {
				$content = (array)$this->content[$i];
				if( !array_diff_key( $this->update, $content ) ) {
					$this->content[ $i ] = ( object ) array_merge( $content, $this->update );
        } else {
          throw new Exception('Update method has an off key');
        }
			}
		}
  }

	/**
	 * Prepares data and written to file
	 * 
	 * @return object $this 
	 */
	public function trigger() {
		$content = (!empty($this->where) ? $this->whereResult() : $this->content);
		$return = false;
		if($this->delete) {
			if(!empty($this->lastIndices) && !empty($this->where)) {
				$this->content = array_filter($this->content, function($index) {
					return !in_array($index, $this->lastIndices);
				}, ARRAY_FILTER_USE_KEY);
	
				$this->content = array_values($this->content);
			}	elseif(empty($this->where) && empty($this->lastIndices)) {
				$this->content = array();
			}
			
			$return = true;
			$this->delete = false;
		}	elseif(!empty($this->update)) {
			$this->_update();
			$this->update = [];
		}	else {
      $return = false;
    }

		$this->commit();
		return $this;
  }

	/**
	 * Flushes indexes they won't be reused on next action
	 * 
	 * @return object $this 
	 */
	private function flush_indexes() {
		$this->lastIndices = array();
  }

	/**
	 * Validates and fetch out the data for manipulation
	 * 
	 * @return array $r Array of rows matching WHERE
	 */
	private function whereResult() {
    $this->flush_indexes();

		if($this->merge == 'AND') {
			return $this->where_and_result();
		}	else {
			$r = [];
			// Loop through the existing values. Ge the index and row
			foreach($this->content as $index => $row) {
				// Make sure its array data type
				$row = (array)$row;
				// Loop again through each row,  get columns and values
				foreach($row as $column => $value) {
					// If each of the column is provided in the where statement
					if(in_array($column, array_keys($this->where))) {
						// To be sure the where column value and existing row column value matches
						if($this->where[$column] == $row[$column]) {
							// Append all to be modified row into a array variable
							$r[] = $row;
							// Append also each row array key
							$this->lastIndices[] = $index;
						} else {
              continue;
            }
					}
				}
			}
			return $r;
		}
  }

	/**
	 * Validates and fetch out the data for manipulation for AND
	 * @return array $r Array of fetched WHERE statement
	 */
	private function where_and_result() {
		/*
			Validates the where statement values
		*/
		$r = [];
		// Loop through the db rows. Ge the index and row
		foreach( $this->content as $index => $row ) {
			// Make sure its array data type
			$row = (array)$row;
			
			//check if the row = where['col'=>'val', 'col2'=>'val2']
			if(!array_diff($this->where,$row)) {
				$r[] = $row;
				// Append also each row array key
				$this->lastIndices[] = $index;			
			} else {
        continue;
      }
    }

		return $r;
  }
  
  // 
	public function orderBy($column, $order = self::ASC) {
		$this->orderBy = [$column, $order];
		return $this;
  }
  
  // 
	private function _processOrderBy($content) {
		if($this->orderBy && $content && in_array($this->orderBy[0], array_keys((array)$content[0]))) {
			/*
				* Check if order by was specified
				* Check if there's actually a result of the query
				* Makes sure the column  actually exists in the list of columns
			*/
			list($sort_column, $orderBy) = $this->orderBy;
			$sort_keys = [];
      $sorted = [];
      
			foreach($content as $index => $value) {
				$value = (array)$value;
				// Save the index and value so we can use them to sort
				$sort_keys[$index] = $value[$sort_column];
			}
			
			// Let's sort!
			if($orderBy == self::ASC) {
				asort($sort_keys);
      }	elseif($orderBy == self::DESC) {
				arsort($sort_keys);
      }

      // We are done with sorting,
      // lets use the sorted array indexes to pull back the original content and return new content
			foreach( $sort_keys as $index => $value ) {
				$sorted[ $index ] = ( array ) $content[ $index ];
      }

			$content = $sorted;
    }

		return $content;
  }

  // 
	public function get() {
		if($this->where != null) {
			$content = $this->whereResult();
		}	else {
			$content = $this->content; 
    }

		if($this->select && !in_array('*', $this->select)) {
      $r = [];

			foreach($content as $id => $row) {
				$row = (array)$row;
				foreach($row as $key => $val) {
					if(in_array($key, $this->select)) {
						$r[$id][$key] = $val;
					} else {
            continue;
          }
				}
      }

			$content = $r;
		}

		$content = $this->_processOrderBy($content);
		
		return $content;
	}
}
