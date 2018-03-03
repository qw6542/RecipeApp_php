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
        $recipe_data =  $request->json(['Recipe']);
        $recipe = new Recipe();
        $recipe -> title = $recipe_data['title'];
        $recipe ->  image = $recipe_data['image'];
        $recipe -> user_id = $recipe_data['user_id'];
        $recipe -> touch();

        $user = User::find($recipe_data['user_id']);
        $recipe = $user -> recipe() -> save($recipe);

        $i_array = $recipe_data['descriptions'];
        for($i=0; $i<sizeof($i_array);$i++){
            $description =  new Description();
            $description -> description = $i_array[$i];
           $recipe -> description() -> save($description);
        }

        $i_array = $recipe_data['ingredients'];
        for($i=0; $i<sizeof($i_array);$i++){
            $ingredient = Ingredient::where('name','=',$i_array[$i])->first();
            if( is_null($ingredient) ) {
                $ingredient = new Ingredient();
                $ingredient->name = $i_array[$i];
                $ingredient= $ingredient ->save();
            }
            $recipe ->  ingredient() -> syncWithoutDetaching($ingredient);
        }

    }

    public function getRecipeByid($id)
    {
        $recipe_data = Recipe::findorfail($id);
        $recipe_data->increment('clicks');
        $recipe_data->update();
        $description_array;
        foreach ($recipe_data->description as $des) {
            $description_array[]=  $des->description;
        }

        $ingredient_array = null;
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

    public function new(  )
    {
        $newRecipes = Recipe::all()->sortByDesc('created_at')->take(10);
        $newRecipes = array("newRecipes" => $newRecipes );
        return json_encode($newRecipes);
    }

    public function hot( )
    {
        $newRecipes = Recipe::all()->sort('clicks')->take(10);
        $newRecipes = array("newRecipes" => $newRecipes );
        return json_encode($newRecipes);
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
     * @param  \App\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function show(Recipe $recipe)
    {
        //
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
