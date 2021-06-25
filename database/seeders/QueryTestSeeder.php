<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\Slug;
use App\Services\Scrapping\Source;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QueryTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->clearTables();

        Schema::disableForeignKeyConstraints();

        $genre = $this->createGenre();
        $author = $this->createAuthor();
        $publisher = $this->createPublisher();

        $book = $this->createBook($publisher, Arr::wrap($author), Arr::wrap($genre));

        Schema::enableForeignKeyConstraints();
    }

    protected function clearTables(): void
    {
        Artisan::call('migrate:refresh');
    }

    protected function createAuthor(): Author
    {
        $author = Author::factory()
            ->create([
                "id" => 1,
                "name" => "Френк",
                "surname" => "Герберт"
            ]);

        $author->slugs()->save(new Slug([
            "id" => 1,
            "slug" => "frank_herbert",
            "source" => Source::Bookclub,
        ]));

        return $author;
    }

    protected function createGenre(): Genre
    {
        $genre = Genre::factory()
            ->create([
                "id" => 1,
                "name" => "Фантастика",
                "description" => "Найкращі книги для справжніх поціновувачів жанру. Тут ви знайдете не лише найпопулярніші новинки від визнаних фантастів, але й цікаві видання, які підкорили читачів з усього світу.",
            ]);

        $genre->slugs()->save(new Slug([
            "id" => 2,
            "slug" => "fantastic_books",
            "source" => Source::Bookclub,
        ]));

        $genre->slugs()->save(new Slug([
            "id" => 3,
            "slug" => "fantastic",
            "source" => Source::Starylev,
        ]));
        return $genre;
    }

    protected function createPublisher(): Publisher
    {
        return Publisher::factory()
            ->create([
                "id" => 1,
                "name" => "«Книжковий Клуб «Клуб Сімейного Дозвілля»"
            ]);
    }

    protected function createBook(Publisher $publisher, array $authors, array $genres)
    {
        $book = Book::factory()
            ->create([
                "id" => 1,
                "publisher_id" => $publisher->id,
                "title" => "Дюна",
                "description" => "«Дюна» зробила Френка Герберта відомим на весь світ і, обігнавши в рейтингах навіть «Володаря перснів», виборола престижні літературні нагороди: премію Г’юґо і премію Неб’юла в категорії «Найкращий роман», премію SFinks як «Книга року», неодноразове визнання від журналу «Локус» у категорії «Найкращий роман усіх часів». Ця культова сага — про вічну боротьбу і жагу до перемоги, про ціну справедливості і вибір шляху.",
                "currency" => "грн",
                "price" => "239",
                "club_price" => "289",
                "code" => "4105197",
                "language" => "українська",
                "original_title" => "Dune",
                "original_language" => "англійська",
                "cover" => "палітурка",
                "pages" => "656",
                "format" => "150x220 мм",
                "details" => "{\"illustrations\":\"кольорові (вклейки)\",\"translators\":\"К. Грицайчук, А. Пітик\",\"series\":\"Хроніки Дюни\",\"weight\":\"820 гр.\"}",
                "isbn" => "978-617-12-7689-5",
                "updated_at" => "2021-06-25T11:24:45.000000Z",
                "created_at" => "2021-06-25T11:24:45.000000Z",
            ]);

        $book->slugs()->save(new Slug([
            "id" => 4,
            "slug" => "dyuna",
            "source" => Source::Bookclub,
        ]));

        foreach ($authors as $author) {
            $book->authors()->save($author);
        }

        foreach ($genres as $genre) {
            $book->genres()->save($genre);
        }

        return $book;
    }
}
