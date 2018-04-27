<?php

namespace App\Http\Controllers;
use App\User;
use App\Recipe;
use App\Description;
use App\Ingredient;
use Illuminate\Http\Request;


class RecipeController extends Controller
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
    public function create(Request $request)
    {
        $recipe_data =$request->all();
        $image_data = explode(',',$request-> image);
        $decoded = base64_decode($image_data[1]);
        if(str_contains($image_data[0], 'jpeg')){
            $extension = 'jpg';
        }
        else {
            $extension = 'png';
        }
        $filename = '/recipeImage/'.str_random().'.'.$extension;
        $path = public_path().$filename;
        file_put_contents($path,$decoded);

        $recipe = new Recipe();
        $recipe -> title = $recipe_data['title'];
        $recipe -> image = $filename;
        $recipe -> user_id = $recipe_data['user_id'];
        $recipe -> method = $recipe_data['method'];
        $recipe -> style = $recipe_data['style'];
        $recipe -> touch();

        $user = User::find($recipe_data['user_id'])->first();

        $user -> recipe() -> save($recipe);

        $recipe = Recipe::all()->last();
        foreach($recipe_data['descriptions'] as &$des) {
            if ($des != []) {

                $description = new Description();
                if (null !== $des['step']) {
                    $description->step = $des['step'];
                    null === $des['detail']? $description->detail = " " :
                        $description->detail = $des['detail'];
                    $description->recipe_id = $recipe->id;
                }

                $description->recipe()->associate($recipe);
                $description->save();

            }
       }

       foreach($recipe_data['ingredients'] as &$i) {
            if( $i != [] ) {
                $ingredient = new Ingredient();
                if(null !== $i['name']) {
                    $ingredient->name = $i['name'];
                null === $i['quantity']? $ingredient->quantity = " " :
                     $ingredient->quantity = $i['quantity'];
                null === $i['measurement']? $ingredient->measurement = " " :
                     $ingredient->measurement = $i['measurement'];
                null === $i['preparation']? $ingredient->preparation = " " :
                    $ingredient->preparation = $i['preparation'];
                null === $i['get_from']? $ingredient->get_from = " " :
                    $ingredient->get_from = $i['get_from'];

                    $ingredient->recipe()->associate($recipe);
                    $ingredient->save();
                }
            }
       }
//            if decide to use one to many relationship
//            $recipe ->  ingredient() -> syncWithoutDetaching($ingredient);


    }


    public function getRecipeById($id)
    {
        $recipe_model = Recipe::findorfail($id);
        $recipe_model->increment('clicks');
        $recipe_model->update();

        $recipe = array( "recipe" => array(
            "id"=> $recipe_model['id'],
            "created_at"=> substr($recipe_model->created_at,0,19),
            "title"=> $recipe_model->title,
            "user_name"=> $recipe_model->user->first()->name,
            "rating" =>$recipe_model->rating,
             "image"=> $recipe_model['image'],
            "ingredients" => $recipe_model->ingredient,
            "descriptions" =>$recipe_model->description,
            "method" => $recipe_model->method,
            "style" => $recipe_model->style
        ));
        return json_encode($recipe);
    }
    //Auxiliary function to add owner name of the recipe
    private function addUserName(&$item) {
        $item["username"]= User::find($item->user_id)->name;
    }

    public function new()
    {
        $newRecipes = Recipe::orderByDesc('created_at')->paginate(5);

        foreach ($newRecipes as &$Recipe){

            $Recipe = $this->addUserName($Recipe);
        }

        foreach ($newRecipes as $Recipe){
            $Recipe['image'] = asset($Recipe['image']);
        }

        return json_encode($newRecipes);
    }

    public function hot( )
    {
        $hotRecipes = Recipe::orderByDesc('clicks')->paginate(5);
        foreach ($hotRecipes as &$recipe){
            $recipe = $this->addUserName($recipe);
        }
        foreach ($hotRecipes as $Recipe){
            $Recipe['image'] = asset($Recipe['image']);
        }

        return json_encode($hotRecipes);
    }
     public  function kitchen($id) {
         $user= User::find($id);
         $recipes = $user -> recipe;
         return json_encode($recipes);
     }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */

    public function search(Request $request) {
        $s = $request['search'];
        $search_data = explode(' ',$s);
        $result = Recipe::orWhere('title','=',$s)
            ->orWhere('title','like','%'.$s.'%')
        ->orWhereHas('ingredient',function ($query) use ($search_data){
           foreach ($search_data as $i){
               $query->orWhere('name','like','%'.$i.'%');
           }
        })->orderBy('rating')->paginate(5);

         return  json_encode($result);
    }
    public function edit(Recipe $recipe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recipe $recipe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipe $recipe)
    {
        //
    }
}
