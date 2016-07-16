fpPHP
=====

The same old fpJava project applied in PHP

A basic library containing the most basic set of functional structures like Seq, Map, Either, FTry (A copy of Scala Twitter Try class), Maybe (Scala Option set of classes) using Monad and Functor as much as possible and avoid $variable = mutacao.

This lis contains a persistence library that tries to emulate Scala Anorm library. In order to use it correctly you must create a file named conf in resources folder placed in application's root folder. This file is a property file like the above sample:

```conf
#/resources/conf.properties
db.urlconn=sqlite:./resources/db.sq3
db.user=username #in case it has
db.pass=password#in case it has
```

The usage is simple (and if you have used Anorm you will find it very similar (that's the main idea ^^)):

```php
function dbSelectSample(){
  FDB::db()->withConnection(
    SQL::sql('select foo from bar where birl = :value')
    ->on(Map::map_(array(':value', 'A value')))
    ->as_(function(Row $row){
      return $row->getColumn('foo')->map(function($foo){return new Bar($foo)});
    })
  );
}
```

This function will return an Either[Bar, String]. And if you in any case missed PDO I simulated a Scala implicit there. The withConnection function requires a function that has one paremeter. The PDO, but the as_ function returns a function that has only one parameter. The PDO so without this caveat we would need to write like that:

```php
function dbSelectSample(){
  FDB::db()->withConnection(function(PDO $pdo){
    return (SQL::sql('select foo from bar where birl = :value')
    ->on(Map::map_(array(':value', 'A value')))
    ->as_(function(Row $row){
      return $row->getColumn('foo')->map(function($foo){return new Bar($foo)});
    }))($pdo);
  });
}
```

The first example is way much simpler. Just ensur to finish with as_ when you want to select something from the Database. Now if you want to insert, delete or update any row, this lib has the executeUpdate that do the same, return a function with PDO as only parameter, but when applied it returns the number of the rows affected by the query.

```php
function dbInsertSample(){
  FDB::db()->withConnection(
    SQL::sql('insert into bar values(:foo, :birl);')
    ->on(Map::map_(array(':foo', 'A foo value'), array(':birl', 'A value')))
    ->executeUpdate()
  );
}
```

This library has it's own autoloader, in case you want to use it, just put the fp folder (inside src directory) and include the autoloader.php file in the script you will call the classes.
This library uses namespace with java style of directories, so if you want to load manually fp\collections\seq\Seq class you must have include the file /fp/collections/seq/Seq.class.php in your file, and it's dependencies like Traversable and Monad files. And you must use the 'use' keyword. If you use the autoloader right, you will not need to require or include any classes, only import with 'use' and actually use it. All classes has simple DSL, you never will need to instantiate manually with 'new'. For Seq you have Seq::seq(1, 2, 3, 4) or Seq::seq('a', 'b', 'c') and for Map you have Map::map_(array('key', 'value'), array('key', 'value')). Php has it's natural tuples: array. So instead of reinventing the wheel I used array syntax as the tuple substitute.

## Further planes

I'm planning to add Set classes (a set of classes that sort the 'internal' array by the key), Range (Something like From(1)->to(10) and return an Seq stating from 1 and finishing in 10) a functional abstract Controller class with methods to do the IO (a.k.a echo and print) in something similar to Scala PlayFramework 
```scala
  def hi = Action(Ok("Hi").as("application/json"))
```
If possible I will try to wrap PHP Curl and Stream function in a function OO style
