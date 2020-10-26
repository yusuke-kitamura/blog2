<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Http\Requests\BlogRequest;

class BlogController extends Controller
{
	/**
	* ブログ一覧を表示
	* @param int $id
	* @return view
	*/

    public function showList(){
    	$blogs = Blog::all();
    	return view('blog.list', ['blogs' => $blogs]);
    }

    // ブログ詳細を表示
    public function showDetail($id){
    	$blog = Blog::find($id);

    	if(is_null($blog)){
    		\Session::flash('err_msg', 'データがありません');
    		return redirect(route('blogs'));
    	}

    	return view('blog.detail',['blog' => $blog]);
    }

    /**
	* ブログ登録画面を表示
	* @return view
	*/

    public function showCreate(){
    	return view('blog.form');
    }

    /**
	* ブログを登録
	* @return view
	*/

    public function exeStore(BlogRequest $request){
    	// データを受け取る
    	$inputs = $request->all();

    	\DB::beginTransaction();
    	try{
			// ブログを登録
	    	Blog::create($inputs);
	    	\DB::commit();
    	}catch(\Throwable $e){
    		\DB::rollback();
    		abort(500);
    	}

		\Session::flash('err_msg', 'ブログを登録しました');
    	return redirect(route('blogs'));
    }

    // ブログ編集フォーム
    public function showEdit($id){
    	$blog = Blog::find($id);

    	if(is_null($blog)){
    		\Session::flash('err_msg', 'データがありません');
    		return redirect(route('blogs'));
    	}

    	return view('blog.edit',['blog' => $blog]);
    }

    /**
	* ブログを更新
	* @return view
	*/

    public function exeUpdate(BlogRequest $request){
    	// データを受け取る
    	$inputs = $request->all();

    	\DB::beginTransaction();
    	try{
			// ブログを登録
	    	$blog = Blog::find($inputs['id']);
	    	$blog->fill([
	    		'title' => $inputs['title'],
	    		'content' => $inputs['content'],
	    	]);
	    	$blog->save();
	    	\DB::commit();
    	}catch(\Throwable $e){
    		\DB::rollback();
    		abort(500);
    	}

		\Session::flash('err_msg', 'ブログを更新しました');
    	return redirect(route('blogs'));
    }

    // ブログ削除
    public function exeDelete($id){
    	if(empty($id)){
    		\Session::flash('err_msg', 'データがありません');
    		return redirect(route('blogs'));
    	}

    	try{
	    	Blog::destroy($id);
    	}catch(\Throwable $e){
			abort(500);
    	}

    	\Session::flash('err_msg', '削除しました');
    	return redirect(route('blogs'));
    }
}
