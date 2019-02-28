<?php
/*
*2018-3-9 14:27:01
*敏感词
*
*/

class FilterWordAction extends BaseAction{
	public function index(){
		//搜索
        if (!empty($_GET['keyword'])) {
			$condition['word'] = array('like', '%' . $_GET['keyword'] . '%');
        }

		$word = M('Filter_word');
        $count = $word->where($condition)->count();
        import('@.ORG.system_page');
        $p = new Page($count, 15);
		$list = $word->field(true)->where($condition)->order('id DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
		$this->assign('word_list',$list);
		$pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
		$this->display();
	}

	public function add_word(){
		if(IS_POST){
			$word = M('Filter_word');
			$data['word'] = $_POST['word'];
			$condition['word'] = array('like', '%' .$data['word'] . '%');
			if($word->where($condition)->find()){
				$this->error('已经添加过了,不能重复添加');
			}
			if($word->add($data)) {
				$this->filter_word_cache();
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！');
			}
		}else{
			$this->display();
		}
	}

	public function del_word(){
		$word = M('Filter_word');
		$id=$_GET['id'];
		$condition['id'] = $id;
		if($word->where($condition)->delete()){
			$this->filter_word_cache();
			$this->success('删除成功！');
		}else {
			$this->error('删除失败！');
		}
	}

	public function edit_word(){
		if(IS_POST){
			$word = M('Filter_word');
			$data['word'] = $_POST['word'];
			$condition['id'] = $_POST['id'];
			if($word->where($condition)->save($data)) {
				$this->filter_word_cache();
				$this->success('编辑成功！');
			}else{
				$this->error('编辑失败！');
			}
		}else{
			$word = M('Filter_word');
			$id=$_GET['id'];
			$condition['id'] = $id;
			$this->assign('word',$word->where($condition)->find());
			$this->display();
		}
	}
	
	
	public function mutil_add(){
		if(IS_POST){
			$words = explode(PHP_EOL,$_POST['words']);
			$words = array_flip($words);
			$words = array_keys($words);
			foreach ($words as $v) {
				$data[]['word'] = $v;
			}

			$word = M('Filter_word');
			if(!$word->addAll($data)){
				$this->error('批量添加失败，请检查数据是否正确');
			}else{
				$this->filter_word_cache();
				$this->success('批量添加成功！');
			}
		}
		$this->display();
	}

	public function filter_word_cache(){
		$word = M('Filter_word');
		$filter_words = M('Filter_word')->where(array('word'=>array('neq','')))->getField('id,word');
		$filter_words = array_values($filter_words);
		S('Filter_words',$filter_words);

	}
}

?>