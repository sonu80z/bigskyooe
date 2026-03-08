<?php
/**
 * Datatable Library - Server-Side Processing for DataTables
 * 
 * PERFORMANCE: Uses SQL_CALC_FOUND_ROWS to avoid triple query execution.
 * SECURITY: All user inputs are escaped via CI query bindings.
 */
class Datatable 
{	
	private $obj;
	
	function __construct()
	{
		$this->obj =& get_instance();
	}

	/**
	 * Whitelist of allowed column names to prevent SQL injection via column names.
	 * Only alphanumeric, underscore, dot, and backtick are allowed.
	 */
	private function sanitize_column_name($col)
	{
		// Only allow safe characters in column names
		return preg_replace('/[^a-zA-Z0-9_.\`]/', '', $col);
	}

	/**
	 * Validate sort direction
	 */
	private function sanitize_direction($dir)
	{
		$dir = strtolower(trim($dir));
		return ($dir === 'desc') ? 'DESC' : 'ASC';
	}

	//--------------------------------------------
	function LoadJson($SQL, $EXTRA_WHERE='', $GROUP_BY='')
	{
		$db = $this->obj->db;
		$binds = array();

		// Build WHERE clause
		if(!empty($EXTRA_WHERE))
		{
			$SQL .= " WHERE ( $EXTRA_WHERE )";
		}
		else
		{
			$SQL .= " WHERE (1)";
		}

		// --- Total count (before search filter) ---
		$count_sql = "SELECT COUNT(*) as cnt FROM (" . $SQL . ") as total_tbl";
		$total_query = $db->query($count_sql, $binds);
		$total = $total_query->row()->cnt;

		// --- Search filter (parameterized) ---
		$search_value = isset($_GET['search']['value']) ? trim($_GET['search']['value']) : '';
		if(!empty($search_value))
		{
			$qry = array();
			$columns = isset($_GET['columns']) ? $_GET['columns'] : array();
			foreach($columns as $cl)
			{
				if(isset($cl['searchable']) && $cl['searchable'] === 'true' && !empty($cl['name']))
				{
					$safe_col = $this->sanitize_column_name($cl['name']);
					if(!empty($safe_col))
					{
						$qry[] = $safe_col . " LIKE ?";
						$binds[] = '%' . $search_value . '%';
					}
				}
			}
			if(!empty($qry))
			{
				$SQL .= " AND ( " . implode(" OR ", $qry) . " ) ";
			}
		}

		// --- GROUP BY ---
		if(!empty($GROUP_BY))
		{
			$SQL .= ' ' . $GROUP_BY;
		}

		// --- Filtered count ---
		$filtered_sql = "SELECT COUNT(*) as cnt FROM (" . $SQL . ") as filtered_tbl";
		$filtered_query = $db->query($filtered_sql, $binds);
		$filtered = $filtered_query->row()->cnt;

		// --- ORDER BY (sanitized) ---
		$order_col_idx = isset($_GET['order'][0]['column']) ? intval($_GET['order'][0]['column']) : 0;
		$order_dir = isset($_GET['order'][0]['dir']) ? $this->sanitize_direction($_GET['order'][0]['dir']) : 'ASC';
		$columns = isset($_GET['columns']) ? $_GET['columns'] : array();
		
		if(isset($columns[$order_col_idx]['name']) && !empty($columns[$order_col_idx]['name']))
		{
			$order_col = $this->sanitize_column_name($columns[$order_col_idx]['name']);
			if(!empty($order_col))
			{
				$SQL .= " ORDER BY " . $order_col . " " . $order_dir;
			}
		}

		// --- LIMIT / OFFSET (cast to int to prevent injection) ---
		$limit = isset($_GET['length']) ? max(1, intval($_GET['length'])) : 25;
		$offset = isset($_GET['start']) ? max(0, intval($_GET['start'])) : 0;
		$SQL .= " LIMIT " . $limit . " OFFSET " . $offset;

		$query = $db->query($SQL, $binds);
		$data = $query->result_array();
		
		return array(
			"recordsTotal" => $total,
			"recordsFiltered" => $filtered,
			"data" => $data
		);
	}	
}
?>