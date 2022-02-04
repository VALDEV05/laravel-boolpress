# Laravel Relationships

## One to Many 


Partiamo dal descrivere il tipo di relazione, parliamo della relazione tra le categoria e gli articoli. All'interno di un blog può esserci una relazione tra un articolo ed una categoria. Ovvero un articolo può essere associato ad una categoria ma una categoria può essere associato a diversi articcoli -> relazione one to many
# In questo esempio parleremo della relazione tra categorie e posts


### Definizione della categoria


Partiamo dal definire il modello la migrazione il controller e il seeder per questa relazione 

``` php artisan make:model -rcsm Models/Category ```
In qesto caso creiamo ```(RC)``` un controller di tipo risorsa con assegnato il modello, un ```(S)``` seeder con il giusto nome, e anche una ```(M)``` migrazione gia precompilata da laravel.

(Potevamo usare anche semplicemente il comando ``` php artisan make:model -a Models/Category ``` --> ma lui creerà anche una factory.)

#### Definizione della struttura della categoria (migrazione)

Questa sarà una tabella molto semplice avremo un name e uno slug.

```Schema::create('categories', function (Blueprint $table) {
  $table->id();
  $table->string('name');
  $table->string('slug');
  $table->timestamps();
});```

migriamo solo la tabella delle categories
```php artisan migrate --path database/migrations/2022_01_29_135055_create_categories_table.php```

#### Definiamo il seeder e popoliamo il db

Definiamo un array di categorie, successivamente le cicliamo e trasformiamo il nome in uno slug nel caso ci fosse bisogno(spazi o lettere maiuscole)


ciclicamo tramite un foreach

```
	$categories = [
            'programming',
            'storytelling',
            'coding',
            'comedy',
            'database',
            'Classics',
            'Didactic',
            'family',
            'trips',
            'DIY',
            'style and fashion',
            'news'
        ];


        foreach ($categories as $category ) {
            $_category = new Category;
            $_category->name= $category;
            $_category->slug= Str::slug($category);
            $_category->save();
        }
```

#### Popoliamo il seeder

```php artisan db:seed --class=CategorySeeder```





### Arrivati qui creaiamo la relazione tra le categorie e i post

###### A post **belongs-to** a category.


Abbiamo detto che un post può essere assiciato ad una categoria, quindi possiamo dire che un post 'appartiene' ad una categoria ovvero (in inglese) -> **belongs-to**. 


Quindi accediamo al modello ```Post.php``` e aggiungiamo la relazione

```
	public function category(): BelongsTo   
    {
        return $this->belongsTo(Category::class);
    }
```


Verifica che con l'aggiunta della relazione venga importata correttamente in questo modo: ```use Illuminate\Database\Eloquent\Relations\BelongsTo;```

###### A category **has-many** posts.

Abbiamo detto che una categoria può essere assegnata a diversi posts, quindi una categoria 'ha molti' posts (in inglese) -> **has-many**. Come fatto per i post entriamo nel modello delle categorie e aggiungiamo la relazione.

Quindi accediamo al modello ```Category.php``` e aggiungiamo la relazione

```
	public function posts():HasMany
	    {
	        return $this->hasMany(Post::class);
	    }
```

Allo stesso modo verifica se è stata correttamente importata la relazione. 
```use Illuminate\Database\Eloquent\Relations\HasMany;```



### Impostiamo la chiave esterna sulla tabella secondaria 
*_Una chiave esterna rappresenta uno o più campi che fanno riferimento alla chiave primaria di un’altra tabella. Lo scopo della chiave esterna è garantire l’integrità referenziale dei dati. Cioè, sono consentiti solo i valori che si ritiene debbano apparire nel database. _*

Tra i posts e le categories la tabella indipendente è quella delle categorie, quindi aggiungeremo li la foreign key('chiave esterna') ``category_id`` che punta all'id della tabella posts.


###### Creeremo una nuova migrazione che aggiunga questa chiave

```php artisan make:migration add_category_id_to_posts_table```


##### Implementiamo la migrazione

```php
public function up()
{
  Schema::table('posts', function (Blueprint $table) {
      $table->unsignedBigInteger('category_id')->nullable()->after('id');
      $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
  });
}

public function down()
{
  Schema::table('posts', function (Blueprint $table) {
      $table->dropForeign('posts_category_id_foreign');
      $table->dropColumn('category_id');
  });
}

```
##### Migriamo la nuova tabella 
```php artisan migrate --path database/migrations/2022_01_29_150536_add_category_id_to_posts_table.php```


#### Inseriamo e aggiorniamo i modelli di una relazione



Attraverso tinker --> ``php artisan tinker`` creiamo delle associazioni tra posts e categories.

##### Associamo un post ad una categoria

Accediamo ad un post
```
	$post = App\Models\Post::find(20); 
```
In questo modo accediamo al post con id 20

Ora selezioniamo una categoria

```
	$category = App\Models\Category::find(5)
```
Associamo la categoria al post

```$category->posts()->save($post);```

Verifichiamo l'associazione

``` $category->posts```

##### Associamo una categoria ad un post

Accediamo alla categoria

```$cat2 = App\Models\Category::find(6);```

Accediamo al post

```$post = App\Models\Post::find(30)```

Associamo la categoria al post

``` $post->category()->associate($cat2)```

Verifichiamo l'associazione

```$post->category```


##### Associamo tutti i post ad una categoria

```php
// Selezioniamo alcuni posts
$posts = App\Post::where('id', '>', 25)->get();
// Selezioniamo una categoria
$cat3 = App\Category::find(3);
// Associamo i posts all categoria
$cat3->posts()->saveMany($posts);
// Verifichiamo
$cat3->posts
``` 


### Aggiungiamo la relazione all'interfaccia grafica tramite CRUD

#### Metodo Create


###### Aggiungiamo alla fillable properties del modello Post

```protected $fillable = ['title', 'image', 'body', 'category_id'];```
Aggiungiamo ```category_id```

###### Modifichiamo il Admin\PostController@create
```
public function create()
	{
	    $categories = Category::all();
	    return view('admin.posts.create', compact('categories'));
	}
```

Aggiungiamo l'accesso alle categorie


###### Mostriamo il form all'interno del admin.posts.create


```
<div class="form-group">
  <label for="category_id">Categories</label>
  <select class="form-control" name="category_id" id="category_id">
      <option selected disabled>Select a category</option>
      @foreach($categories as $category)
      	<option value="{{$category->id}}">{{$category->name}}</option>
      @endforeach

  </select>
</div>
```


###### Verifichiamo se il valore è arrivato al metodo Admin\PostController@store 


```ddd($request->all());```


###### Aggiungiamo la regola di validazione per la categoria Admin\PostController@store

```'category' => 'nullable|exists:categories,id'```


###### Creiamo il post con l'associazione

```Post::create($validateData);
	return redirect()->route('admin.posts.index');
```


#### Metodo Edit

Stesso procedimento del create cambia solamente il form.






### Mostrare tutte le categorie 


##### Rotta che ci mostra tutte le categorie
 

//categories/{category}/posts -> CategoryController@posts


```Route::get('categories/{category}/posts', CategoryController@posts)->name('categories.posts');```


Siamo fuori da admin--> dobbiamo

Creiamo il controller per le categorie ```php artisan make:controller CategoryController -m Models/Category```  a cui associamo il modello.

All'interno ci creiamo una funzione che risponde al metodo ``@posts`` 

```
	/**
     * Show all posts associated with a category
     */

    public function posts(Category $category)
    {
       $posts = $category->posts()->orderByDesc('id')->paginate(12);
       return view('guest.categories.posts', compact('posts','category'));
    }
```

##### Ci creiamo la view ``guest.categories.posts`` dove all'interno cicliamo tutti i posts appartenenti alla categoria.
```@extends('layouts.app')


@section('content')
    <div class="p-5 bg-light">
        <div class="container">
            <header class="d-flex justify-content-center">
                <div id="title">
                    <h1 class="text-center">Category: <span class="fw-bolder">{{ $category->name }}</span></h1>
                    <p class="lead text-center">All posts of this category</p>
                </div>
                <div class="close-view ml-auto pt-4">
                    <a class="btn btn-outline-primary btn-lg" href="{{ route('posts.index') }}"><i class="fa fa-backward"></i></a>
                </div>
            </header>
            <div class="row">
                @forelse ($posts as $post)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card mt-4" style="height: 350px">
                            <img class="card-img-top" src="{{ $post->cover }}" alt="">
                            <div class="card-body d-flex flex-column justify-content-between text-center">
                                <h2 class="card-title">{{ $post->title}}</h2>
                                <p class="card-text">{{ $post->sub_title }}</p>
                                <a class="btn btn-outline-primary btn-lg mb-1" href="{{ route('posts.show', $post->slug) }}">View More </a>
                            </div>
                        </div>
                    </div> 
                    {{-- /.col --}}
                @empty
                <div class="col">
                    <p>Vuoto</p>
                </div>
                @endforelse
            </div>
            {{-- /.row --}}
            <div class="paginate d-flex justify-content-center mt-5">
                {{ $posts->links() }}
            </div>
    </div>
@endsection```

##### Modifichiamo all'interno dello show->
	
```<p><em>Category: {{ $post->category ? $post->category->name : 'Uncategorized'}}</em></p>```
nella versione estesa con una modifica


```
	<div class="metadata"
		<div class="category"
			@if ($post->
				Category: <a class="text-decoration-none text-dark" href="{{ route('categories.posts', $post->category->slug) }}">{{ $post->category->name }}</a
			@else
				<span class="text-muted text-uppercase">Uncategorized</span>
          @endif
      	</div>
  </div>
```


#### Aggiungiamo alla view index un indice per muoversi all'interno delle categorie

Modifichiamo la view guest.posts.index una sidebar e all'interno una card 



 
 ```<aside class="col-2">
                <div class="card mb-2">
                    <div class="card-body">
                        <h3>
                            Categories
                        </h3>

                        <ul>
                            @foreach($categories as $category)
                            <li>
                                <a href="{{ route('categories.posts', $category->slug) }}">{{$category->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </aside>```
            
            
            
   dobbiamo passare le categorie alla view  entriamo nel postcontroller lato guest e aggiungiamo: ```$categories = Category::all();``` e al compact ```'categories```
   
   
 Ora avremo una sidebar (lato guest) funzionante per muoverci nelle categorie
 
 


## Categorie lato admin

Creata la risorsa che punta a una vista fittizzia all'interno della dashboard ``layouts.admin``

aggiunto il link

```<a class="nav-link text-decoration-none text-dark" aria-current="page" href="{{route('admin.categories.index')}}">
                                    <i class="fas fa-code-branch fa-lg fa-fw"></i>
                                    Categories
                                </a>```
 Creato il gruppo di rotte che gestirà lato admin 
 	```Route::resource('categories', CategoryController::class);```
 	
 Creiamo il controller 'CategoryController'
 ```php artisan make:Controller CategoryController -rm Models/Category```
 
 
 Entro nel controller 
 
 @index -> **devo 'returnare la view' e passargli le categorie**

```$categories = Category::all();
        return view('admin.categories.index', compact('categories'));```
        
Creo la view
## Problema 
##### Target class [App\Http\Controllers\Admin\App\Http\Controllers\CategoryController] does not exist. 

Risolto eliminando dal file web.php use ``App\Http\Controllers\CategoryController;``

Completato il form e la lista 
```<div class="container">
        <h1 class="text-center my-5"><i class="fas fa-code-branch fa-lg fa-fw"></i> Add a new category <i class="fas fa-code-branch fa-lg fa-fw"></i></h1>
        <div class="row flex-column">
            <div class="col-12">
                {{-- form per la creazione di nuove categorie --}}
                <form action="{{route('admin.categories.store') }}" method="post">
                    @csrf

                    <div class="mb-3 d-flex flex-column align-items-center justify-content-center">
                      <label for="name" class="form-label d-flex justify-content-center">Category</label>
                      <input type="text" name="name" id="name" class="form-control w-75 "  placeholder="Type a category name her" aria-describedby="nameHelper">
                      <small id="nameHelper" class="text-muted d-flex justify-content-center pt-3">Type a category name, max 200</small>
                    </div>
                    <div class="save d-flex justify-content-center">
                        <button type="submit" class="btn btn-outline-primary btn-lg">Add Category</button>
                    </div>
                </form>
            </div>
            <div class="col-12">
                <h3 class="text-center my-5"><i class="fas fa-code-branch fa-lg fa-fw"></i> See all categories <i class="fas fa-code-branch fa-lg fa-fw"></i></h3>
                <ul class="list-group w-25 m-auto">
                    @foreach ($categories as $category)
                        <li class="list-group-item text-center bg-dark text-light text-uppercase">{{ $category->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>```


Implementiamo il metodo @create

step 1 
``ddd($request->all());`` 
step 2 
Validazione 
``$validate = $request->validate([
            'name'=> 'required'
        ]);``
step 3
Creazione slug
``$validate['slug']= Str::slug($validate['name']);``
step 4 
``ddd($request->all(), $validate);``
Step 5 
Salvataggio
``Category::create($validate);``
Step 6 
redirect
``return redirect()->route('admin.categories.index');``


#### Errore
Add [name] to fillable property to allow mass assignment on [App\Models\Category]. 

aggiungere all'interno del modello
``protected $fillable = ['name', 'slug'];``

#### /Errore

Completiamo la crud come sappiamo fare



# Relazione post -> user one to many
Un post può essere scritto da un solo utente, mentre un utente può scrivere più post.


Aggiungo la relazione HasMany al modello **User.php**
```
/**
* Get all of the posts for the user
* 
* @return \Illuminate\Database\Eloquent\Relations\HasMany
*/
public function posts(): HasMany
{
return $this->hasMany(Post::class);
}
```
E aggiungiamo anche la parte inversa al modello **Posts.php**
```
/**
    * Get all of the user for the post
    * 
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function user(): BelongsTo   
    {
        return $this->belongsTo(User::class);
    }
```

Dobbiamo aggiungere la chiave esterna, ovvero aggiungere la chiave alla tabella dipendente anche in questo caso la tabella dipedente è la tabella posts quindi ->

```php artisan make:migration add_user_id_to_posts_table``` e ci formiamo la nostra tabella

metodo up
```
   Schema::table('posts', function (Blueprint $table) {
       $table->unsignedBigInteger('user_id')->nullable()->after('id');
       $table->foreign('user_id')->references('id')->on('users');
    });
```
metodo down
```
	$table->dropForeign('post_user_id_foreign');
	$table->dropColumn('user_id');
```

Migriamo

Dobbiamo un secondo user, facciamo un logout e una nuova registrazione.
Impostiamo tutti i records ad un utente tramite tinker

`php artisan tinker`
Mi prendo il primo utente
$admin = User::find(1);
Mi prendo tutti i post 
$posts = Post::all();

$admin->posts()->saveMany($posts)


*Se hai un errore nel User Models\Post*

Ora però tutti gli utenti posso guardere e mordificare i post anche di altri utenti quindi
### Metodo @index
nel PostController e verifichiamo quale utente è autenticato e prendo i post relativi solo ad esso "mediante la relazione" 

```$posts = Auth::user()->posts()->orderByDesc('id')->paginate(10);```

Allo stesso modo dobbiamo aggiungere il controllo per il metodo store.

### Metodo @store

Al metodo store dobbiamo aggiungere il valore dell'id autenticato
``$validated['user_id'] = Auth::id();``
e aggiungere la fillable in Post.php

### Metodo @edit
``
if(Auth::id()== $post->user_id){
	restituisco la view
}else{
	abort(403);
}``

### Metodo @update
```
if(Auth::id()== $post->user_id){
	CI COPIAMO TUTTO
}else{
	abort(403);
}```

### Metodo @destroy
Stessa cosa


Per Bloccare la registrazione di altri utenti;

Auth::routes(['register' => false]);


# Relazione Posts tags
La relazione in questione sarà una many to many, ovvero sappiamo che ogni post può avere molti tag e un tag può essere associato a diversi posts. Nella relazione many to many sappiamo che non esiste la chiave esterna, ma una tabella pivot
metodo belongstoMany a tutti e due i modelli
Esempio: 
	nel modello User inseriamo una funzione roles() per identificare una relazione con il modello Role(). IN quanto un utente può avere diversi 	ruoli.
	All'inverso nel modello Role inseriamo una funzione users() in cui restituiamo la stessa relazione con il modello User. Dato che un utente può 	avere diversi ruoli
Creazione della tabella ponte
	  esiste una convenzione ovvero si uniscono le tabelle in ordine analfabetico al singolare 
	  ``php artisan make:migration create_role_user_table``
	  all'interno della migrazione dobbiamo impostare: 
	  	```$table-›unsignedBigInteger('user id') :
			$table-›foreign ('user id')-›references ('id') -›on ('users");
			
			$table-›unsignedBigInteger('roleid'):
			$table-›foreign('role id') -›references ('id') -›on ('roles"):```
Per scirivere all'intrno della tabella pivot possiamo utilizzare il metodo attach() per eliminare quei recors usiamo il metodo detatch
Per aggiungere ed eliminare contemporaneamente dei record all’interno della tabella pivot possiamo utilizzare il metodo sync()

la funzione sync() accetta come parametro un array di id da inserire all'interno della tabella ponte gli id già presenti nella tabella ponte e che non si trovano nell'array verranno rimossi, insomma tipo update.


# Iniziamo 
## Relazione tra posts e tags | Many to Many
Aggiungendo la relazione al modello Post 

importiamo tramite snippet relazione belongsToMany
``/**
     * The tags that belong to the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
``

Verifichiamo che sia importata a inizio pagina correttamente
``use Illuminate\Database\Eloquent\Relations\BelongsToMany;``


Creazione del modello tag

``php artisan make:model -a Models/Tag``


stesso passaggio per il modello tag
```/**
     * The posts that belong to the Tag
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
```
Verifichiamo che sia importata a inizio pagina correttamente
``use Illuminate\Database\Eloquent\Relations\BelongsToMany;``



definizione della migrazione e del seeder
Migrazione:
	``	$table->id();
      	$table->string('name');
      	$table->string('slug');
      	$table->timestamps();``
 Simile categoria
 
creazione seed
``$tags = [
            'community', 
            'fullstack',
            'developer',
            'webDevelopment',
            'laravel'
        ];
        foreach ($tags as $tag) {
            $_tag = new Tag;
            $_tag->name = $tag;
            $_tag->slug = Str::slug($_tag->name);
            $_tag->save();
        }
``
ricordati di importare il modello tag e la classe Str
``use App\Models\Tag;
use Illuminate\Support\Str;
``

Migriamo e seediamo
``php artisan migrate``
``php artisan db:seed --class=TagSeeder``

Aggiungiamo la tabella pivot

``php artisan make:migration create_post_tag_table``

Alla tabella pivot assegniamo i solidi record tramite unsignedBigInteger
 ```$table->unsignedBigInteger('post_id')->nullable();
    $table->unsignedBigInteger('tag_id')->nullable();```

e impostiamo anche la relazione della chiave esterna
```$table->foreign('post_id')->references('id')->on('posts')->cascadeOnDelete();
  	$table->foreign('tag_id')->references('id')->on('tags')->cascadeOnDelete();```
In questo modo sto dicendo che la relazione viene cancellata se il tag o il post vengono eliminati.


Ora migriamo 
``php artisan migrate``


Andimao ad implementare la crud
Aggiungiamo nel admin.posts.create il multiple select
```<div class="mb-3">
                  <label for="tags" class="form-label">Tags</label>
                  <select multiple class="form-select" name="tags[]" id="tags">
                    <option disabled> Select all tags</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                  </select>
                </div>
                ```
                
 Entriamo nel PostController e aggiungiamo al @create i tags ``  $tags = Tag::all();`` e passiamoli con compact
 
 Assiucrati che siano importato i modelli
 
 
 Dato che possono esserci diversi tags verifica che all'interno  del form select nel nome aggiungi il simbolo dell'array in questo modo name="tags[]"


Verifichiamo e validiamo 
$post = Post::create($validate);
e poi aggiungiamo i tags se esistono

``if ($request->has('tags')) {
            $request->validate([
                'tags' => ['nullable', 'exists:tags,id']
            ]);
            $post->tags()->attach($request->tags);
        }``

# aggiungiamo la lista dei tags nella sidebar del guest.posts.index

``<div class="card mb-2">
                    <div class="card-body">
                        <h3>
                            Tags
                        </h3>
                        <ul>
                            @foreach($tags as $tag)
                            <li>
                                <a href="#">{{$tag->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
 </div>``

Passiamo i tags al PostController guest ` $tags = Tag::all();` e poi tramite compact li passo.


Creazione della rotta
``/* route to show all tags */
Route::get('tags/{tag:slug}/posts', 'TagController@posts')->name('tags.posts');``


Passiamo lo slug al modello tag
``/**
     * Get the route key for the model
     * 
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }``
Entriamo nel TagController e aggiungiamo metodo @posts ed aggiungiamo la rotta all'index

creo metodo @posts
`` /**
     * Show all posts associated with a category
     */
    public function posts(Tag $tag)
    {
       $posts = $tag->posts()->orderByDesc('id')->paginate(12);
       return view('guest.tags.posts', compact('posts', 'tags'));
    }``
creo la view copiamo e incolliamo la categories.posts e modifichiamo a piacimento


# Completo la crud 
aggiungo nel layouts.admin il link per la page Tags, mi creo il controller 
``php artisan make:controller Admin/TagController -rm Models\Tag``

mi creo le rotte di tipo resource
``Route::resource('tags', TagController::class);``

modifico la rotta nel link dei tags ``{{ route('admin.tags.index) }}``

nel @index faccio un return della view

``return view ('admin.tags.index');``

Gli passo i tags con il compact

metodo create
	non esiste in quanto il form è all'interno di index
metodo store
	creato il form fatto puntare a questo metodo implementiamo la validazione dei dati
	implementiamo le fillable properties
metodo edit
	dopo aver aggiungto il form implentiamo la validazione
metodo destroy
	implementato anche il metodo destroy

# Aggiunta nell'edit la possibilità di aggiungere i tags
Aggiungo il form multiplo, valido i dati
``<div class="mb-3">
                  <label for="tags" class="form-label d-flex justify-content-center">Tags</label>
                  <select multiple class="form-select w-100 d-flex justify-content-center"  name="tags[]" id="tags">
                    <option disabled> Select all tags</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                  </select>
                </div>``
Validazione:
	``if ($request->has('tags')) {
                $request->validate([
                    'tags' => ['nullable', 'exists:tags,id']
                ]);
                $post->tags()->sync($request->tags);
            }``


# Aggiungere delle risorse dall'utente (File/Foto)

Dato che l'applicazione quando sarà online si avrà accesso solo alla cartella public allora dovremo far in modo di indirizzare l'utente ad una porzione della nostra applicazione nella quale sono presenti le immagini abbiamo accesso a un filesystem che ci permette di salvare i file all'interno di quella porzione oveero storage.app.public e li saranno accessibili dall'utente ma serve un link per collegarla in public.

Primo step è quello di accedere all'interno config/filesystem.php e modificare il tipo di driver nella riga 16 modifichiamo da 'local' -> 'public' riavviamo artisan serve
Successivamente dovremo creare il link simbolico tra la public/storage e la cartella storage/app/public eseguendo il comando ```php artisan storage:link```

All'interno di public avremo il collegamento NON UNA CARTELLA!!



Ora dobbiamo modificare il form di create, per far accettare dei file

al form nella sezione cover basterà cambiare il type da text a file. 

Per far si che il form accetti dei file dovremo aggiungere dopo il metodo l'attribituo **enctype** *enctype="multipart/form-data"*

In questo modo alla backend non verrrà trasmesso solamente il nome del file, ma anche tutta una serie di dati importanti del file

Possiamo aggiungere un primo filtro per i file accettati impostando all'interno dell'input accept="images/*" ovvero che accetta tutti le tipologie di immagini (.svg,.png,.jpg)

togliere <!-- value="{{ old('cover') }}" --> dato che violerebbe la sicurezza dell'utente in quanto ricorrerebbe ad un vecchio path di immagini. ma prorpio il type="file" non permetterebbe ciò.


Modifica della validazione
    All'interno di Admin/PostController @store modifico la validazione 
    ``'cover'=> ['required'],``-->    
    ```'cover'=> ['required', 'image', 'max:200'],```

    Devo creare il percorso dell'imagine
    
    ``$cover_path = Storage::put('post_images', $request->file('cover'));``
    verifico che la classe storage sia importata correttamente 
    
        `use Illuminate\Support\Facades\Storage;`
    
    Devo aggiungere il percorso dell'immagine ai dati validati
        ``//passo il percorso immagine ai dati validati
            $validate['cover'] = $cover_path; ``


# modifico anche l'edit

dato che sto modificando, ci dobbiamo ricordare di eliminare l'immagine precedente.  //cancellazione percorso immagine precedente
                Storage::delete($post->cover);

Completata la validazione devo camviare il percorso delle immagini
parto dallo show  src="{{ asset('storage/' . $post->cover) }}"
e cosi via per tutte le altre page dove ci sono le immagini in questo modo però vedremo solo queste aggiunte, dovremo modificare il seeder e far scaricare le immagini nella cartella.



Modifica al seeder  

$post->cover = 'placeholders/' . $faker->image('public/storage/placeholders',1200,480,'Posts', false, true, $post->title);

il false serve a non far salvare tutto il percorso ma solo il nome dell'immagine, e dato che abbiamo salvato solo il nom e dovremo concatenare la cartella corrispondente dove cercare tutto 'placeholders/'

migriamo e seediamo insiema

``php artisan migrate:fresh --seed``


se per caso hai come errore qualcosa del genere devi sbloccare i permessi della cartella storage
    Cannot write to directory "public/storage/placeholders" 

lo facciamo in questo modo: sudo chmod -R ug+rwx <nomeCartella>

Basterà aggiungere la cartella nella storage/app/publix

```



## Modifica per continuare il lavoro

Ho sbloccato le registrazioni 

Dato che ho fatto il ``php artisan migrate:fresh --seed`` -> dovrò reimpostare tutti i post all'user_id 1 per visualizzarli 
entro in tinker -> mi salvo tutti i post in una variabile posts (``$posts = Post::all();``) successivamente mi prendo lo user a cui voglio assegnare tutto nel mio caso, ovvero voglio prendere il primo utente loggato (``$admin = User::first()``) ora devo assegnare tutti i post ad admin (``$admin->posts()->saveMany($posts)``)

# AGGIUNTA EMAIL


impariamo a inviare una email tramite laravel --> [Documentazione reltiva](https://laravel.com/docs/7.x/mail#configuration)


Esistono diversi driver che gestiscono le email, quello classico e locale ovvero un driver che intercetta tutte le email su un file di log in locale ('storage/logs/laravel.log'). Oppure il driver 'smtp' che gestisce tutte le email tramite servizi esterni. Come Gmail, Mail trap e molti altri.



Qualsiasi driver venga usato i passaggi successivi saranno gli stessi.
# Generiamo la Mail
    - Creiamo un oggetto MAILABLE che rappresenta il messaggio che vogliamo inviare
        ``php artisan make:mail <nomeOggettoMailable>``
        - Questo comando ci creerà una classe che estenderà Mailable e ci creerà un costruct e una funzione build dove restituiremo la view.
        Il construct serve per passare dei dati alla mail.
    -Dopo aver costruito tutta l'email passiamo all'invio della mail tramite un controller attraverso l'utilizzo della facade Mail dove possiamo specificare a chi inviare l'email e soprattutto il contenuto di essa. 
    RICORDIAMO DI IMPORTARE CORRETTAMENTE SIA L'OGGETTO MAIL CHE LA FACADE MAIL
    
   
# Primi step

*Obiettivo Creare una pagina contatti con all'interno un form dove aggiungere e scrivere l'email.*


Creo una rotta all'interno del file web.php
``Route::get('contacts', 'PageController@contacts')->name('guest.contacts');``

Creo il controller
``php artisan make:controller PageController``

Creo il metodo contacts che mi restituisce la view
``
	public function contacts()
    {
        return view('guest.contacts.form');
    }
``

Creo la view form

Modifico il layout.app
	   <a class="nav-link" href="{{ route('guest.contacts') }}"><i class="fas fa-phone-alt  fa-lg fa-fw "></i></a>
    
creo il form

	``@extends('layouts.app
	@section('content')
    <div class="p-5 bg-light">
        <div class="container">
            <h2 class="text-center display-3"><i class="fa fa-phone-alt fa-lg fa-fw"></i> Contacts <i class="fa fa-phone-alt fa-lg fa-fw"></i></h2>
            <p class="text-center text-uppercase text-muted lead">we will help you if you need</p>
        </div>
        <div class="container">
            <form action=" " method="post">
                @csrf

                <div class="mb-4 w-75 m-auto">
                    <div class="row">
                        <div class="col">
                            <label for="name" class="form-label d-flex justify-content-center">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Mario Rossi" aria-describedby="nameId" minlength="4" maxlength="50">
                            <small id="NameId" class="text-muted d-flex justify-content-center">Type your name | max:50</small>
                        </div>
                        <div class="col">
                            <label for="email" class="form-label d-flex justify-content-center">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="mariorossi@exaple.com" aria-describedby="emailId" required>
                            <small id="emailId" class="text-muted d-flex justify-content-center">Type your email</small>
                        </div>
                       
                    </div>
                    
                </div>
                <div class="mb-3 w-75 m-auto">
                    <label for="body" class="form-label d-flex justify-content-center">Type your message</label>
                    <textarea class="form-control" name="body" id="body" rows="5" placeholder="Type youre message"></textarea>
                </div>
                <div class="send d-flex justify-content-center mt-5">
                    <button type="submit" class="btn btn-primary w-25 text-uppercase py-3">Send</button>
                </div>
            </form>
        </div>
    </div>``
    
Creo il metodo che gestisce la rotta come se fosse un form per un metodo post


- Mi creo la rotta
	``/* route to send the form */
	Route::post('contacts', 'PageController@sendContactForm')->name('guest.contacts.send');``
- Creo il metodo
	`public function sendContactForm(Request $request){ddd($request->all());}`
- Valido tutti i dati

-Devo generare la mail
``php artisan make:mail ContactFormMail``
Creerà una cartella mail in app con l'oggetto maillable appena creato 
Da documentazione laravel ci dice che ogni funzione publica createa e successivamente passata nel costruct verrà mandata anche nel build

Questo ci permette nella view appena creata in views/mail/contacts/lead.blade.php di accedere ai dati come un semplice array

In questo caso cambio il mail_mailer=log per far si che intercetto le email sul file log

Generiamo l'email 
	Mail::to('admin@example.com')->send(new ContactFormMail($validated));
	//ddd($request->all());
   //ddd($validated);
   //return(new ContactFormMail($validated))->render();
   return redirect()->back()->with('message', 'hai inviato una mail');
   
    
Aggiungiamo nel form i messagi e gli errori


# Possiamo utilizzare del markdown per il template for the mailable

Lui creeerà una struttura per markdown per la nostra email

``php artisan make:mail MarkdownContactFormMail --markdown=mail.contacts.mdlead``


 uesto file markdown è estendibile e configurabile a piacimento.
 
 [Documentazione](https://laravel.com/docs/7.x/mail#customizing-the-components)
 
 Nel file MarkdownContactFormMail -> rifacciamo i stessi passaggi fatti nel ContactFormMail in questo caso dato che abbiamo utilizzato il markdown temolate di laravel aggiungiamo anche un link che riporti alla guest.home `->with(['url' => route('guest.home')]);` 
 
 Per migliorare la resa grafica aggiungiamo un componente 
	@component('mail::panel')
	Name:{{ $data['name'] }}
	Email:{{ $data['email'] }}
	@endcomponent
	
Al componente bottone aggiugniamo l'url della homepage

## Appunti validazioni
//Mail::to('admin@example.com')->send(new ContactFormMail($validated));
//ddd($request->all());
//ddd($validated);
//return(new ContactFormMail($validated))->render();
//return(new MarkdownContactFormMail($validated))->render();

### Per customizzare questi componenti esiste un comando che installa una cartella all'interno del nostro scaffolding
``php artisan vendor:publish --tag=laravel-mail`` 
All'intero della cartella views aggiunge una cartella vendor con all'interno tutti i template utilizzati da laravel per generare queste mail.


# Aggiunta di un client per email come Mailtrap

Ti registri, c'è un select sotto integrations selezione 'laravel 7+' e copi quello che esce nel caso username e pwd c'è un reset credentials.
``
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null``


Ora se inviassimo una mail mailtrap al posto nostro intercetterà la mail

# cambiamo il Controller che gestisce le email

`php artisan make:model -cm Contact`

Creiamo la migrazione

```
$table->id();
$table->string('name', 50);
$table->string('email');
$table->text('message');
$table->timestamps();
```

Modifichiamo il metodo store in modo che crei uan risorsa contatto ovvero 
``$contact = Contact::create($validated);``
Al mail:to passiamo `` Mail::to('admin@example.com')->send(new MarkdownContactFormMail($contact));``

Modifichiamo MarkdownContactFormMail in modo che accetti un'istanza dell'oggeto Contact

public $contact; 
/**
* Create a new message instance.
*
* @return void
*/
public function __construct(Contact $contact)
{
    $this->contact = $contact;
}

Ovviamente dobbiamo fare delle modifiche anche nel mdlead (sostituire data con contact)

Aggiugniamo le fillable properties su Models/Contact
``protected $fillable = ['name', 'email', 'message'];``



# BONUS 1
- Implementate lato admin una view index per far vedere all'amministratore tutti i messaggi ricevuto dall'applicazione.
    Aggiungete view show per mostrare il contentuto del messaggio ricevuto



# BONUS 1 COMPLETATO
- Implementata lato admin una view index (admin.contacts.index) che al click del bottone all'interno di un messaggio 
ti porta allo show  (admin.contacts.show)


# BONUS 2
- Nella pagina show del messaggio (lato admin) implementate un form per inviare una risposta al messaggio ricevuto. Questo richiederá la generazione di una mailable differente e di inviare il messaggio all'utente chevi ha contattato.

Completato aggiunto un form all'interno della view show cretata una nuova mailable e fatta gestire dal Admin/ContactController@store da cui generiamo un email di tipo markdown che inviamo alla stessa email del mittente è come se avessi creato un """""""tread"""""""








# Laravel/API/Vue Parte 1
[JSON DOCUMENTATION](https://laravel.com/docs/7.x/responses#json-responses)


- Creiamo un endpoint nel file routes/api.php per visualizzare in formato json una lista di posts

- COMPLETATO Creato un endpoint per visualizzare tutti i posts in formato JSON [ENDPOINT](http://127.0.0.1:8000/api/posts)



- possiamo utilizzare due metodi per ottenere delle risorse in formato json: la scorciatioa con return oppure response()->json()

- COMPLETATO ho provato ad impostare entrambi i metodi

- Provo la paginazione e l'aggiunta della categoria User in quanto è l'unica popolata
- Problema con il collegamneto delle relazioni


- Create un controller per gestire l'azione della rotta api in un namespace dedicato alle API..
- Agginto il controller Api/PostController

### Definire una Risorsa API Eloquent

Trasforma facilmente i modelli Elonquent in un JSON

[Documentazione](https://laravel.com/docs/7.x/eloquent-resources#generating-resources)

Creazione della Risorsa
``php artisan make:resource PostResource``

importo all'interno di web.php 
``use App\Http\Resources\PostResource;``


- Commentate via il metodo che avete scelto sopra per sevice le risorse in formato json e sostituitelo con una Eloquent API resource.
- Ricordatevi che, se il nostro endpoint deve restituire una collection di risorse laravel consiglia di usare ::collection() oppure di creare una risorsa di tipo collection
- La risorsa api eloquent la possiamo creare con `make:resource` 
    se aggiungiamo l'opzione `--collection` oppure al nome della classe aggiungiamo il prefisso `Collection` ad laravel crea una resource collection.
    ``php artisan make:resource`` PostResource crea una risorsa normale `php artisan make:resource PostCollection` crea una risorsa di tipo collection.

- COMPLETATO Creato L'endpoint per una richiesta con allegata la relazione delle categorie

Aggiunti tramite tinker delle relazione: ``$model->relation()->attach(idToAttach)`` Oppure per eliminare una relazione usiamo il detach  
``$model->relation()->detach(idToDetach)``
``/* 
    Metodo "Lungo"
Route::get('posts', function(){
    $posts = Post::all();
    return response()->json([
        'response' => $posts
    ]);
}); */


/* 
    Senza paginazione
Route::get('posts', function(){
    $posts = Post::all();
    return $posts;
}); */

/* 
    Con la paginazione
Route::get('posts', function(){
    $posts = Post::paginate(10);
    return $posts;
}); */


/**
 * Rotta con paginazione e relazione users 
 * 
 * *Problema con il collegamento della relazione user*
 */
/* Route::get('posts', function(){
    $posts = Post::with(['user'])->get();
    return $posts;
});
 */
``


# Fatto l'endpoint passate all'utilizzo di Vue:

Avendo utilizzato la laravel ui vue durante l'installazione iniziale allora è già installato

Lo richiamo utilizzando il pacchetto frontend NPM
Se non lo avevi fatto in precedenza, sennò ti elimina tutti i settagi di webpackmix
`php artisan ui vue npm install && npm run dev npm run watch`


- create un componente per mostrare un'elenco di posts in una nuova rotta.

Dato che dovrò creare tramite vue un altro blog creo all'interno del menu un link con icona vue.
 Creata la view la identifico tramite una rotta all'interno del file web.php


`/* Route to the blog with vueAPI   */
Route::get('/SPAposts', function(){
return view('guest.SPAposts.index');
})->name('guest.SPAposts.index');`


con lo scaffolding scaricato all'inizio abbiamo installto tutta la parte di vuejs ovvero app.js e admin.js che useremo per usufruire delle nostre API.
All'interno di questi file possiamo notare come viene già impostato e preparato l'istanza di VUEJS e il collegamento a un componente di esempio `<example-component></example-component>`
Volendo per visualizzarlo potremmo aggiungerlo alla nostra pagina.


`resources/views/guest/SPAposts/index.blade.php`
Breve descrizione della struttura della pagina:
- abbiamo esteso il layout `('layouts.app')`
- Abbiamo collegato tramite lo yield content 
- Abbiamo aggiunto il componente d'esempio



@extends('layouts.app')
@section('content')

    <div class="p-5 bg-light">
        <div class="container text-center">
            <h1 class="display-3"><i class="fab fa-vuejs fa-lg fa-fw"></i> SPA BLOG <i class="fab fa-vuejs fa-lg fa-fw"></i></h1>
            <p class="lead text-muted"><i class="fab fa-forumbee"></i> Qui mostreremo tutta la lista dei post stampati tramite l'utilizzo di un API <i class="fab fa-forumbee"></i></p>
            <hr class="my-2">

            <example-component></example-component>
        </div>
    </div>
@endsection



In questo modo visualizzeremo il componente esempio di vue.
Il componente di esempio è come i componenti che abbiamo già utilizzato tramite VUECLI ovvero sono formati da <template> - <script> - <style>.


Costruiamoci un componente tutto nostro che utilizzeremo per mostrarci tutte le nostre risorse 


## Creazione di un componente tutto nostro:

    - Aggiungiamo la registrazione del componente all'interno del file app.js dove copieremo la riga 22 ovvero dove viene registrato il example-component

    ``Vue.component('example-component', require('./component/ExampleComponent.vue').default);``

    E la personalizzeremo a modo nostro il componente sarà posts-list in quanto dovrà presentare una lista di articoli di un blog.

    Vue.component('posts-list', require('./components/PostsListComponent.vue').default);


    Ci dovremo creare questo componente a mano. `resources/js/components` path completo dove creare il componente

    creato il componente lo aggiungiamo alla nostra view `<posts-list></posts-list>`

    aggiunto il componente passiamo alla creazione del template 

        <template>

        </template>

        <script>
            export default {

            }
        </script>

        <style>

        </style> 

    possiamo passare a fare la chiamata axios tramite lo script:
    
    <script>
        export default {
            mounted(){
                axios
                    .get('api/posts')
                    .then(response => {
                        console.log(response.data.data);
                    })
            }
        }
    </script>
    

    In questo modo all'interno della console potremo visualizzare i dati passati dall'endPoint. 
    - Con questo console.log(response.data.data); avremo solamente i post di cui abbiamo bisogno, ma se togliessimo i data 
    mostreremo un console.log(response); di questo genere avremo in più tutti quei dati di cui potremo usufruire come:
    - in caso di paginazione i link per la paginazione
    - i metadata ovvero tutti quei dati di informazioni come pagina corrente numero di elementi per pagina totale dei posti e molto altro.


    Dato che l'ogetto game ancora deve essere definito in questo momento non funzionerà 

    definiamolo:
        <script>
            export default {
                data(){
                    return{
                        loading: true,
                        posts: {},
                        meta: {},
                        links: {},
                    };
                },
                mounted(){
                    axios
                        .get('api/posts')
                        .then(response => {
                            console.log(response.data.data);
                            this.posts = response.data.data;
                            this.meta = response.data.meta;
                            this.links = response.data.links;
                            this.loading = false;
                        })
                }
            }
        </script>
    Nel nostro caso aggiungeremo anche delle altr variabili come loading che useremo per il caricamento nell'attesa del caricamento della pagina, una variabile dove salveremo tutti i nostri posts, una dove salveremo  links per la paginazione e una per salvare tutti i metadata

    Passiamo nel mostrare a schermo tutti i posts
    basterà solamente richiamare la struttura dei posts

    <template>
        <section class="posts row">
            <div class="post col-md-4" v-for="post in posts">
                <div class="card">
                    <div class="card-body">
                        <h3>{{ post.title }}</h3>
                        <p class="text-muted">{{ post.title }}</p>
                    </div>
                </div>
            </div>
        </section>
    </template>

- visto che ci siete, nella stessa pagina mostrate anche categorie e tags 
    aggiunta la relazione con le categorie, ma non sono riuscito ad aggiungere quella con i tags ho provato con:
    <p class="mb-0">
        Tags : {{ post.tags }}
    </p>
    Ma non riesco poi a prendere i nomi, mi stampa solo tutto l'array



# Implementazione applicazione a singola pagina
 

- Partiamo dal principio, cos'è un'applicazione a singola pagina?
    È un'applicazione web in cui il contenuto viene caricato dinamicamente senza dover ricaricare la pagina. Passeremo da una pagina all'altra senza mai aspettare il tempo in cui la pagina refresha. Ovviamente nel caso in cui il nostro server API avesse un ritardo o comunque fosse lento nel mostrarci la nostra risorsa in quel caso non attenderemo su una pagina bianca in attesa di caricamento ma attenderemo solo il caricamento della risorsa. Magari possiamo aggiungere un simbolo di download nell'attesa del caricamento della risorsa.

-  Per implementare questa applicazione a singola pagina dobbiamo aggiungere una nuova libreria che fa parte  dell'ecosistema di vuejs. La libreria in questione è **Vue Router** [documentazione](https://router.vuejs.org/) che tramite il file web.php intercetta le rotte e utilizzandi i componenti che ci mette a disposizione ci permette di mostrare questa esperienza utente al cliente. Appunto è il pacchetto ufficiale di VueJS per quanto riguarda la gestione delle rotte.

- L'installazione può avvenire o tramite CDN o tramite installazione da CLI passando tramite il pacchetto NPM 
    `npm i vue-router` poi dobbiamo inniettarlo all'interno di Vue all'interno del file app.js


    `import Vue from 'vue'
     import VueRouter from 'vue-router'
     Vue.use(VueRouter)`


## Setup vue router

// 0. If using a module system (e.g. via vue-cli), import Vue and VueRouter
// and then call `Vue.use(VueRouter)`.

// 1. Define route components.
// These can be imported from other files
const Foo = { template: '<div>foo</div>' }
const Bar = { template: '<div>bar</div>' }

// 2. Define some routes
// Each route should map to a component. The "component" can
// either be an actual component constructor created via
// `Vue.extend()`, or just a component options object.
// We'll talk about nested routes later.
const routes = [
  { path: '/foo', component: Foo },
  { path: '/bar', component: Bar }
]

// 3. Create the router instance and pass the `routes` option
// You can pass in additional options here, but let's
// keep it simple for now.
const router = new VueRouter({
  routes // short for `routes: routes`
})

// 4. Create and mount the root instance.
// Make sure to inject the router with the router option to make the
// whole app router-aware.
const app = new Vue({
  router
}).$mount('#app')

// Now the app has started!



# STEP 1 Definizione dei componenti
- Eseguito lo step dell'installazione possiamo passare allo step della definizioni delle route pages components ovver nel file app.js definiamo delle rotte per delle pagini che sono simili a dei componenti. 


`const Home = Vue.component('Home', require('./pages/Home.vue').default);
 const About = Vue.component('About', require('./pages/About.vue').default);
 const Contacts = Vue.component('Contacts', require('./pages/Contacts.vue').default);`

# STEP 2 Definizione delle rotte
Definite anche le pagine passiamo alla definizione delle rotte
    Abbiamo detto che utilizzando questa applicazione a signola pagina i componenti intercetteranno delle rotte e noi dovremo solamente servire il nome della rotta e il nome della pagina da restituire e faranno tutto i componenti di vue.router come ad esempio `<router-link to="/foo">Go to Foo</router-link>` questo componente ha la stessa funzionalità di un anchor tag `<a href="rotta-da-eseguire">nome da mostrare</a>`


Definizione delle rotte
const routes = [
    {
        path: '/', //URI
        name: 'home', //name della rotta
        component: Home //componente da restituire '''''view'''''
    },
    {
        path: '/about', //URI
        name: 'about', //name della rotta
        component: About //componente da restituire '''''view'''''
    },
    {
        path: '/contacts', //URI
        name: 'contacts', //name della rotta
        component: Contacts //componente da restituire '''''view'''''
    }
]


# STEP 3 Creazione del'istanza vue epassagio del parametro Routes

// 3. Create the router instance and pass the `routes` option
const router = new VueRouter({
    routes
})

# STEP 4 Assicuriamoci che Vue router sia correttamente inniettato all'interno dell'istanza

const app = new Vue({
    router,
    el: '#app',
});

Basterà aggiungere 'router' all'interno dell'istanza



# Dato che abbiamo anche la sezione di autenticazione di laravel ci creiamo un nuovo layout 
Questo lo chiameremo spa.blade.php dove modificheremo solo la navbar di lato sinistro, in quanto non vogliamo toccare la parte della navbar con le autenticazioni di laravel.
Creato il nuovo layout seguiamo con la creazione di un parziale dove metteremo la nostra navbar completa. La parte sinistra modifichiamo i link con i router-link in questo modo
`<router-link to="/contacts" class="nav-link">Contacts</router-link>` Semplificheremo anche il layout dell'app a singola pagina importando solamente la navbar e lo yield (leveremo il tag del main)
Il nostro obiettivo è quello di creare un componente che come quando utilizzavamo Vue Cli Ci gestisce un po il tutto infatti all'interno della cartella resources/js creeremo un componente chiamato App.vue dove estendere il nostro template base di vue


All'interno del nostro dovremo aggiungere il componente che si preoccupa di gestire tutte le rotte `<router-view></router-view>` che ovviamente andrà importato all'interno del nostro file `app.js`-> in questo modo: `Vue.component('App', require('./App.vue').default);`

Dovremo mostrare tutto ciò: utilizzeremo la rotta che laravel imposta di default ovvero `guest.welcome` dove importeremo il componente App e il layout a singola pagina
`@extends('layouts.spa')
@section('content')
  <App></App>
@endsection`

In questo modo potremo visualizzare l'inizio della nostra applicazione a singola pagina, ovvero vedremo che anche switchando link della pagina il browser non ricaricherà mai.
Possiamo notare come all'interno del link ci sia un hash ovvero quel cancelletto che è nel nostro link `http://127.0.0.1:8000/#/contacts`. Possiamo togliere impostando come modalità di default `History Mode` adesso come proprieta di default è impostata la `Hash mode`

Per modificare questa modalità basterà aggiungere ` mode: 'history',` dove abbiamo creato l'istanza.


Ora dovremo costruire una nuova modalità di rotta, una che cattura tutto infatti sarà di tipo catch ha la funzione di catturare tutte le rotte, solo dobbiamo fare attenzione perchè dovrà essere **sempre la nostra ultima rotta** in quanto non legge le rotte successive.
Non mostrerebbe neanche le rotte admin

Route::get('/{any}', function () {
    return view('guest.welcome');
})->where('any', '.*');

# Creiamo il componente per il blog
const Blogs = Vue.component('Blogs', require('./pages/Blogs.vue').default);
Ovviamnente ci aggiungiamo la rotta
{
    path: '/blogs', //URI
    name: 'blogs', //name della rotta
    component: Blogs //componente da restituire '''''view'''''
}

**ERROR in ./resources/js/app.js**
Ovviamente NPM non troverà il componente, va creato.
Aggiungiamo un link per accedere al blog
<!-- <li class="nav-item">
    <router-link class="nav-link" to="/blogs">Blogs</router-link>
</li> -->

All'interno di questo nuovo componente verrà usufruita l'endpoint della risorsa e verra stampata una lista di articoli
- faremo la chiamata axios alla nostra rotta
- successivamente stamperemo a schermo i nostri posts


# Stampare a schermo il singolo articolo

Aggiungiamo una rotta che dinamica che ci mostrerà il singolo articolo
{
    path: '/blogs/:slug', //URI
    name: 'blogPost', //name della rotta
    component: BlogPost //componente da restituire '''''view'''''
}

Questa nuova rotta ha l'aggiunta di un parametro che sarà colui che ci permetterà di collegarci al singolo post
Ci definiamo il componente per il singolo gioco 
Dove all'inizio metteremo solamente la stampa del parametro presa da {{ $route.params.slug }}
`<template>
  <div class="blog">
      <h1>Single game</h1>
      <h4>{{ $route.params.slug }}</h4>
  </div>
</template>`
Aggiungendo uno slug alla fine dell'url avremo il nostro singolo elemento

`http://127.0.0.1:8000/blogs/sed-aperiam-sequi-ut`

# Aggiungiamo il link a button more view

Al nostro bottone aggiungere il solito componente che sostituisce gli anchor-tag ovvero <router-link></router-link>
dove come rotta che risponde all'attributo `:to="rotta"` concateniamo una stringa formata dalla stringa blogs e dal parametro raccolto tramite lo slug in questo modo: `<router-link :to="'/blogs/' + post.slug">View More</router-link>`. 

cliccando questo link ora vedremo il nuovo componente ovvero il singolo articolo. A cui dovremo aggiungere una chiamata API che ci restituisca il contenuto dell'articolo.

aggiungiamo alla chiamata API nel file API.php
`Route::get('posts/{post}', 'Api\PostController@show');`
e al controller `Api\PostController@show` ho aggiunto una nuova risorsa in questo modo: `return new PostResource($post);`. Ora controllo mediante l'utilizzo di postaman il nuovo url generato e vediamo che ci riporta la risorsa corretta.

Facciamo la chiamata Api singola
`export default {
  data(){
    return{
      game:{}
    }
  },
  mounted(){
    axios.get('/api/posts/' + this.$route.params.slug )
    .then((response) => {
      console.log(response.data.data)
    }).catch(error =>{
      console.error(error);
    });
  }
}`

nella console vedremo il post



# MIGLIORIE LATO DESIGN 
- LATO GUEST
   <!--  RIDISEGNA LA NAV -> SEMMAI CREA UN PARZIALE PER SEPARARE LE COSE -->
    <!-- MODIFICA L'ASPETTO DEI LINK -->
- LATO ADMIN
    - SEZIONI MESSAGGI
        <!-- MODIFICA ASPETTO DELLE CARD MARGINE SUPERIORE  -->
        MODIFICA IL TITOLO AGGIUNGI EMOTICON
        AGGIUNGI shadow-lg a tutte le card
