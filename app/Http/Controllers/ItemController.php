<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class ItemController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 商品一覧
     */
    public function index()
    {
        // 商品一覧取得
        $items = Item::all();

        return view('item.index', compact('items'));
    }

    /**
     * 商品登録
     */
    public function add(Request $request)
    {
        // POSTリクエストのとき
        if ($request->isMethod('post')) {
            // バリデーション
            $this->validate($request, [
                'name' => 'required|max:100',
            ]);

            // 商品登録
            Item::create([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'type' => $request->type,
                'detail' => $request->detail,
            ]);

            return redirect('/items');
        }

        return view('item.add');
    }

    public function edit($id) {
        $item = Item::find($id);

        return view('items.edit')->with([
            'item' => $item,
        ]);
    }

    public function itemEdit(Request $request)
    {
        if ($request->isMethod('POST')) {
            // バリデーション
            $this->validate($request, [
                'name' => 'required|max:100',
            ]);

            // Item::where('id' , '=', $request->id)->first()->update;
            // // 商品編集
            // $item->update([
            //     'user_id' => Auth::user()->id,
            //     'name' => $request->name,
            //     'type' => $request->type,
            //     'detail' => $request->detail,
            $item = Item::where('id', '=', $request->id)->first();
            $item->update([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'type' => $request->type,
                'detail' => $request->detail,
            ]);

            return redirect('/items');
        }

        return view('item.edit',['item' =>Item::where('id' , '=', $request->id)->first()]);
    }

    public function itemDelete($id)
    {
        // 既存のレコードを取得して削除する
        $item = Item::where('id' , '=', $id)->first();
        $item->delete();

        return redirect('/items');
    }
}
