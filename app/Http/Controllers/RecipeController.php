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
            if($des != [] ) {
                $description = new Description();
                $description->step = $des['step'];
                $description->detail = $des['detail'];
                $recipe->description()->save($description);
            }
       }

       foreach($recipe_data['ingredients'] as &$i) {
            if($i != [] && $i['name']!= '' ) {
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
        $recipe_model = Recipe::findorfail($id);
        $recipe_model->increment('clicks');
        $recipe_model->update();

        $recipe = array( "recipe" => array(
            "created_at"=> substr($recipe_model->created_at,0,19),
            "title"=> $recipe_model->title,
            "user_name"=> $recipe_model->user->first()->name,
            "rating" =>$recipe_model->rating,
             "image"=> $recipe_model['image'],
            "ingredients" => $recipe_model->ingredient,
            "descriptions" =>$recipe_model->description
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

    public function search(Request $request) {

        $search_data = explode(" ",$request-> all()['search']);
        $result = array();
        foreach ($search_data as $i){
            $recipe = Recipe::where('title','LIKE', '%'.$i)->get();
            if ($recipe != null) {
                array_push($result, $recipe);
            }
        }
        if(sizeof($result) < 5) {
            foreach ($search_data as $i){
                $ingredient = Ingredient::where('name','LIKE', '%'.$i)
                    ->orWhere('name','LIKE', '%'.$i)
                    ->get();
                if ($ingredient != null) {
                    foreach ($ingredient  as $i) {
                        $recipe = Recipe::find($i->recipe_id);
                        array_push($result, $recipe);
                    }
                }
            }
        }


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
