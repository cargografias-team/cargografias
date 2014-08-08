<?php

class ModelListCache {
	
	protected $Instance = null;
	
	static public function list_of($Model){
		$instance = new self();
		return $instance->get_cache_of($Model);
	}
	
	public function get_cache_of($Model){
		$result = Cache::read($Model, 'model_list');
		if(!$result){
			$result = $this->model_list($Model);
            Cache::write($Model, $result, 'model_list');
        }
        return $result;
	}
	
	public function model_list($Model){
		App::uses($Model, 'Model');
		$model = new $Model;
		$result = $model->find('list', array(
			'order' => $model->display_field,
		));
		return  array_values(array_unique($result));
	}
}

?>