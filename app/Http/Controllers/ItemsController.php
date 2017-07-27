<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //postされたデータを変数に入れる
        $keyword = request()->keyword;
        
        //Itemインスタンスを入れる入れ物を用意
        $items = [];
        
        //変数に何か入っていたら
        if ($keyword) {
            //クライアントを作成
            $client = new \RakutenRws_Client();
            
            //アプリケーションIDを設定する
            $client->setApplicationId(env('RAKUTEN_APPLICATION_ID'));
            
            //検索ワードを設定し実行する。結果を変数$rws_responseに入れる
            $rws_response = $client->execute('IchibaItemSearch', [
                'keyword' => $keyword,
                'imageFlag' => 1,
                'hits' => 20,
            ]);
            
            //取得したデータを1つずつ出してItemインスタンスにして$itemsに追加する。
            foreach ($rws_response->getData()['Items'] as $rws_item) {
                $item = new Item();
                $item->code = $rws_item['Item']['itemCode'];
                $item->name = $rws_item['Item']['itemName'];
                $item->url = $rws_item['Item']['itemUrl'];
                $item->image_url = str_replace('?_ex=128x128', '', $rws_item['Item']['mediumImageUrls'][0]['imageUrl']);
                $items[] = $item;
            }
            
            return view('items.create', [
                'keyword' => $keyword, //ユーザーが入力したキーワード
                'items' => $items, //検索結果
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
