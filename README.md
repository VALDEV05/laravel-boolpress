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

Basterà aggiungere la cartella nella storage/app/public