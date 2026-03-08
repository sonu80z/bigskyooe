<?php
/**
 * Data_cache Library
 * 
 * Provides file-based caching for reference data that rarely changes
 * (states, facilities, procedures, ICD-10, payers, insurance types).
 * 
 * This eliminates repeated full-table queries on every page load.
 * Cache TTL defaults to 1 hour. Call invalidate() when data is modified.
 * 
 * Usage in controllers:
 *   $this->load->library('data_cache');
 *   $states = $this->data_cache->get_states();
 *   $facilities = $this->data_cache->get_facilities();
 */
class Data_cache {
    
    private $CI;
    private $default_ttl = 3600; // 1 hour
    
    public function __construct()
    {
        $this->CI =& get_instance();
        // Ensure cache driver is loaded
        if(!isset($this->CI->cache)) {
            $this->CI->load->driver('cache', array('adapter' => 'file'));
        }
    }
    
    /**
     * Get all states (cached)
     */
    public function get_states()
    {
        $key = 'ref_all_states';
        $data = $this->CI->cache->file->get($key);
        if($data === FALSE) {
            $this->CI->load->model('admin/state_model', 'state_model');
            $data = $this->CI->state_model->get_all_states();
            $this->CI->cache->file->save($key, $data, $this->default_ttl);
        }
        return $data;
    }
    
    /**
     * Get active states (cached)
     */
    public function get_active_states()
    {
        $key = 'ref_active_states';
        $data = $this->CI->cache->file->get($key);
        if($data === FALSE) {
            $this->CI->load->model('admin/state_model', 'state_model');
            $data = $this->CI->state_model->get_active_states();
            $this->CI->cache->file->save($key, $data, $this->default_ttl);
        }
        return $data;
    }
    
    /**
     * Get states as lookup array keyed by fldSt (cached)
     */
    public function get_states_lookup()
    {
        $key = 'ref_states_lookup';
        $data = $this->CI->cache->file->get($key);
        if($data === FALSE) {
            $states = $this->get_states();
            $data = array();
            foreach($states as $st) {
                $data[$st['fldSt']] = $st;
            }
            $this->CI->cache->file->save($key, $data, $this->default_ttl);
        }
        return $data;
    }
    
    /**
     * Get all facilities (cached)
     */
    public function get_facilities()
    {
        $key = 'ref_all_facilities';
        $data = $this->CI->cache->file->get($key);
        if($data === FALSE) {
            $this->CI->load->model('admin/facility_model', 'facility_model');
            $data = $this->CI->facility_model->get_all_facilites();
            $this->CI->cache->file->save($key, $data, $this->default_ttl);
        }
        return $data;
    }
    
    /**
     * Get facilities as lookup array keyed by id (cached)
     */
    public function get_facilities_lookup()
    {
        $key = 'ref_facilities_lookup';
        $data = $this->CI->cache->file->get($key);
        if($data === FALSE) {
            $facilities = $this->get_facilities();
            $data = array();
            foreach($facilities as $f) {
                $data[$f['id']] = $f;
            }
            $this->CI->cache->file->save($key, $data, $this->default_ttl);
        }
        return $data;
    }
    
    /**
     * Get all procedures (cached)
     */
    public function get_procedures()
    {
        $key = 'ref_all_procedures';
        $data = $this->CI->cache->file->get($key);
        if($data === FALSE) {
            $this->CI->load->model('admin/procedure_model', 'procedure_model');
            $data = $this->CI->procedure_model->get_all_procedures();
            $this->CI->cache->file->save($key, $data, $this->default_ttl);
        }
        return $data;
    }
    
    /**
     * Get procedures as lookup array keyed by id (cached)
     */
    public function get_procedures_lookup()
    {
        $key = 'ref_procedures_lookup';
        $data = $this->CI->cache->file->get($key);
        if($data === FALSE) {
            $procedures = $this->get_procedures();
            $data = array();
            foreach($procedures as $p) {
                $data[$p['id']] = $p;
            }
            $this->CI->cache->file->save($key, $data, $this->default_ttl);
        }
        return $data;
    }
    
    /**
     * Get all payers (cached)
     */
    public function get_payers()
    {
        $key = 'ref_all_payers';
        $data = $this->CI->cache->file->get($key);
        if($data === FALSE) {
            $this->CI->load->model('admin/payer_model', 'payer_model');
            $data = $this->CI->payer_model->get_all();
            $this->CI->cache->file->save($key, $data, $this->default_ttl);
        }
        return $data;
    }
    
    /**
     * Get all insurance types (cached)
     */
    public function get_insurance_types()
    {
        $key = 'ref_all_insurance_types';
        $data = $this->CI->cache->file->get($key);
        if($data === FALSE) {
            $this->CI->load->model('admin/insurance_type_model', 'insurance_type_model');
            $data = $this->CI->insurance_type_model->get_all();
            $this->CI->cache->file->save($key, $data, $this->default_ttl);
        }
        return $data;
    }
    
    /**
     * Get all insurance companies (cached)
     */
    public function get_insurance_companies()
    {
        $key = 'ref_all_insurance_companies';
        $data = $this->CI->cache->file->get($key);
        if($data === FALSE) {
            $this->CI->load->model('admin/insurance_company_model', 'insurance_company_model');
            $data = $this->CI->insurance_company_model->get_all();
            $this->CI->cache->file->save($key, $data, $this->default_ttl);
        }
        return $data;
    }
    
    /**
     * Get lists by name (cached)
     */
    public function get_lists_by_name($name)
    {
        $key = 'ref_lists_' . md5($name);
        $data = $this->CI->cache->file->get($key);
        if($data === FALSE) {
            $this->CI->load->model('admin/lists_model', 'lists_model');
            $data = $this->CI->lists_model->get_all_lists_by_name($name);
            $this->CI->cache->file->save($key, $data, $this->default_ttl);
        }
        return $data;
    }
    
    /**
     * Get all divisions by type (cached)
     */
    public function get_divisions($type = 0)
    {
        $key = 'ref_divisions_type_' . $type;
        $data = $this->CI->cache->file->get($key);
        if($data === FALSE) {
            $this->CI->load->model('admin/division_model', 'division_model');
            $data = $this->CI->division_model->get_all_divisions_via_type($type);
            $this->CI->cache->file->save($key, $data, $this->default_ttl);
        }
        return $data;
    }
    
    /**
     * Get technologists (role 8 OR also_technologist=1) (cached)
     */
    public function get_technologists()
    {
        $key = 'ref_technologists';
        $data = $this->CI->cache->file->get($key);
        if($data === FALSE) {
            $this->CI->load->model('admin/user_model', 'user_model');
            $data = $this->CI->user_model->get_technologists_all();
            $this->CI->cache->file->save($key, $data, $this->default_ttl);
        }
        return $data;
    }
    
    /**
     * Get ordering physicians (users with role 7) (cached)
     */
    public function get_ordering_physicians()
    {
        $key = 'ref_ordering_physicians';
        $data = $this->CI->cache->file->get($key);
        if($data === FALSE) {
            $this->CI->load->model('admin/user_model', 'user_model');
            $data = $this->CI->user_model->get_users_via_role(7);
            $this->CI->cache->file->save($key, $data, $this->default_ttl);
        }
        return $data;
    }
    
    /**
     * Invalidate all reference data caches
     * Call this when any reference data is modified (facility created, procedure added, etc.)
     */
    public function invalidate_all()
    {
        $this->CI->cache->file->clean();
    }
    
    /**
     * Invalidate a specific cache key
     * @param string $key
     */
    public function invalidate($key)
    {
        $this->CI->cache->file->delete($key);
    }
    
    /**
     * Invalidate facility-related caches
     */
    public function invalidate_facilities()
    {
        $this->CI->cache->file->delete('ref_all_facilities');
        $this->CI->cache->file->delete('ref_facilities_lookup');
    }
    
    /**
     * Invalidate procedure-related caches
     */
    public function invalidate_procedures()
    {
        $this->CI->cache->file->delete('ref_all_procedures');
        $this->CI->cache->file->delete('ref_procedures_lookup');
    }
    
    /**
     * Invalidate user-related caches (technologists, physicians)
     */
    public function invalidate_users()
    {
        $this->CI->cache->file->delete('ref_technologists');
        $this->CI->cache->file->delete('ref_ordering_physicians');
    }
}
