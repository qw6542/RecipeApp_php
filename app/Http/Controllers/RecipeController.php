<?php

namespace App\Http\Controllers;
use App\User;
use App\Recipe;
use App\Description;
use App\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
         return json_encode($request->all());

        $recipe_data =$request->all();
        $image_data = explode(',',$request-> image);
        $decoded = base64_decode($image_data[1]);
        if(str_contains($image_data[0], 'jpeg')){
            $extension = 'jpg';
        }
        else {
            $extension = 'png';
        }
        $filename = str_random().'.'.$extension;
        $path = public_path().'/recipeImage/'.$filename;
        file_put_contents($path,$decoded);

        $recipe = new Recipe();
        $recipe -> title = $recipe_data['title'];
        $recipe -> image =$path;
        $recipe -> user_id = $recipe_data['user_id'];
        $recipe -> touch();

        $user = User::find($recipe_data['user_id'])->first();

         $user -> recipe() -> save($recipe);


        foreach($recipe_data['descriptions'] as &$des){
            if($des != []) {
                $description = new Description();
                $description->step = $des['step'];
                $description->detail = $des['detail'];
                $recipe->description()->save($description);
            }
       }

       foreach($recipe_data['ingredients'] as &$i) {
            if($i != []) {
                $ingredient = new Ingredient();
                $ingredient->name = $i['name'];
                $ingredient->quantity = $i['quantity'];
                $ingredient->measurement = $i['measurement'];
                $ingredient->preparation = $i['preparation'];
                $ingredient->get_from = $i['get_from'];
                $recipe->ingredient()->save($ingredient);
            }
       }
//            if decide to use one to many relationship
//            $recipe ->  ingredient() -> syncWithoutDetaching($ingredient);


    }


    public function getRecipeById($id)
    {
        $recipe_data = Recipe::findorfail($id);
        $recipe_data->increment('clicks');
        $recipe_data->update();

        $description_array = array();
        foreach ($recipe_data->description as $des) {
            $description_array[]=  $des->description;
        }

        $ingredient_array = array();
        foreach ($recipe_data->ingredient as $ins) {
            $ingredient_array[]=  $ins->name;
        }

        $recipe = array( "recipe" => array(
            "title"=> $recipe_data->title,
            "user_name"=> $recipe_data->user->first()->name,
            "rating" =>$recipe_data->rating,
             "image"=> $recipe_data['image'],
            "ingredients" => $ingredient_array,
            "descriptions" => $description_array
        ));
        return json_encode($recipe);
    }
    //Auxiliary function to add owner name of the recipe
    private function addUserName(&$item) {
        $item = $item["username"]= User::find($item->user_id)->name;
    }

    public function new( )
    {
        $newRecipes = Recipe::all()->sortByDesc('created_at')->take(10);

        foreach ($newRecipes as &$Recipe){

            $Recipe = $this->addUserName($Recipe);
        }
        $newRecipes = array(
            "recipes" => $newRecipes
        );
        return json_encode($newRecipes);
    }

    public function hot( )
    {
        $hotRecipes = Recipe::all()->sortByDesc('clicks')->take(10);
        foreach ($hotRecipes as &$recipe){
            $recipe = $this->addUserName($recipe);
        }
        $hotRecipes = array("recipes" => $hotRecipes );
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
