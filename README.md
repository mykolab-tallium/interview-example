## Test examples

## Example #1:
```php
class Post extends Model
{
    ...

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
```
### Controller
```php
    public function index()
    {
        return view('posts.index');
    }
```
### Blade
```php
    @foreach (Post::all() as $post)
        {{ $post->category->name }}
    @endforeach
```

## Example #2:
### Controller
```php
public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'banner' => 'image',
            'category_id' => 'required',
        ]);

        if (auth()->guest() || auth()->user()->hasRole('moderator')) {
            return redirect('/posts')->with('error', 'Permission denied');
        }

        $post = new Post();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->category_id = $request->category_id;
        $post->author_id = auth()->id();
        $post->save();

        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            Image::make($banner)
                ->resize(600, 400, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(storage_path('app/public/posts/banner') . DIRECTORY_SEPARATOR . $post->id . '.' . $banner->extension());
        }

        return redirect('/posts')->with('message', 'Post successfully created.');
    }
```
## Example #3:
### Controller
```php
public function register(RegisterRequest $request)
    {
        $user = User::create($request->all());
        Auth::login($user);
        return redirect('/home');
    }
```
### User model
```php
class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
```

## Example #4:
### users table has more than 500000 records.
```php
    public function handle()
    {
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            echo $user->first_name;
            // Perform some action on user
        }
    }
```

## Example #5:
```php
class ProcessUsersJob implements ShouldQueue
{
    ...

    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            if (! $user->hasRole('moderator')) {
                throw new Exception('Permission denied.');
            }
        }
    }
}
```
