fpPHP
=====

The same old fpJava project applied in PHP

A basic library containing the most basic set of functional structures like Seq, Map, Either, FTry (A copy of Scala Twitter Try class), Maybe (Scala Option set of classes) using Monad and Functor as much as possible and avoid $variable mutation.

##Maybe
```php
use fp\maybe\{Just, Nothing, Maybe};

function div(int $a, int $b): Maybe{
  if($b == 0){
    return Nothing::nothing();
  } else {
    return Just::just($a / $b);
  }
}

echo div(2, 0)
  ->map(function($x){return $x * 3;})
  ->getOrElse(function(){return "Impossible to divide by zero";});
// Echo Impossible to divide by zero
```
The above example shows how to use The basics of Maybe as a simple container to work with simple errors. It tries to use as much as possible the lazy evaluation
using functions to handle the inside value instead of checking with if and else, decreasing the amount of code to be written

##Seq
```php
use fp\collections\seq\Seq;

echo Seq::seq(1, 2, 3, 4, 5)
  ->map(function($x){return $x * 3})
  ->filter(function($x){return $x < 8;})
  ->flatMap(function($x){return Seq::seq(6, 7, 8, 9, 10)->map(function($y) use($x){return $x + $y;})})
  ->foldLeft(0, function($acc, $x){ return $acc + $x;});
```
Seq is the equivalent of a functional array, and the above example shows how simple it is to work with using the OO syntax. In this example I multiplied all item by 3, and then I have filtered only the numbers lesser than 8,
and then I summed all that left with another Seq showing the power of the flatMap with map and finally I joined all numbers in a single number with the help of the foldLeft method

##Map
```php
use fp\collections\map\Map;

echo Map::map(array('name', 'kleber'), array('surname', 'stender'), array('job', 'dev'))
  ->map(function($tuple){
    list($key, $value) = $tuple;
    return array($key, ucfirst($value));
  })
  ->get('name')->getOrElse(function(){return 'Key not found';});
// Echo Kleber
```
Map is an array like structure with hold "tuples" instead of simple values. It does not care about the position in the array since you provide it's key to get the value, and instead of throwing an Exception the method get returns 
a Maybe instance so you can deal with the value with map, flatMap or getOrElse. Otherwise it is a normal array, so you can filter, map, fold, etc.

##Either

```php
  use fp\either\{Left, Right, Either};

  function div($a, $b): Either {
    if($b === 0){
      return Left::left('You cannot divide anything by zero');
    } else {
      return Right::right($a / $b);
    }
  }

  echo div(4, 2)->fold(
    function($error){
      return $error;
    },
    function($result){
      return "4 / 2  = $result";
    }
  );
  //Echo 4 / 2  = 2
```

Either is a structure similar to Maybe, but instead of focusing on the value, it give you the chance
to control both error and non error values. It is a way of returning two possible values.
(In the above example String and Double, represented by Either[String, Double]). There is nothing
that says to you which the is error and which is the value, but the default use is to set errors in the Left side
and values in the Right side. Don't be fooled by the name, it can't return two values. It only carries
one value, either the value or the error. It has only one process method: fold. And it 
gets two parameters, one is a function to be called if the object is a Left and the other a function
to be called if the object is a Right.

##Persistence mini-lib

This lib contains a persistence library that tries to emulate Scala Anorm library. In order to use it correctly you must create a file named conf in resources folder placed in application's root folder. This file is a property file like the below sample:

```conf
#/resources/conf.properties
db.urlconn=sqlite:"./resources/db.sq3"
db.user="username" #in case it has
db.pass="password"#in case it has
```

The usage is simple (and if you have used Anorm you will find it very similar (that's the main idea ^^)):

```php
function dbSelectSample(){
  FDB::db()->withConnection(
    SQL::sql('select foo from bar where birl = :value')
    ->on(array(':value', 'A value'))
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
    ->on(array(':value', 'A value'))
    ->as_(function(Row $row){
      return $row->getColumn('foo')->map(function($foo){return new Bar($foo)});
    }))($pdo);
  });
}
```

The first example is way much simpler. Just ensure to finish with as_ when you want to select something from the Database. Now if you want to insert, delete or update any row, this lib has the executeUpdate that do the same, return a function with PDO as only parameter, but when applied it returns the number of the rows affected by the query.

```php
function dbInsertSample(){
  FDB::db()->withConnection(
    SQL::sql('insert into bar values(:foo, :birl);')
    ->on(array(':foo', 'A foo value'), array(':birl', 'A value'))
    ->executeUpdate()
  );
}
```

This library has it's own autoloader, in case you want to use it, just copy the fp folder in root folder and include the autoloader.php file in the script you will call the classes.
This library uses namespace with java style of directories, so if you want to load manually fp\collections\seq\Seq class you must have include the file /fp/collections/seq/Seq.class.php in your file, and it's dependencies like Traversable and Monad files. And you must use the 'use' keyword. If you use the autoloader right, you will not need to require or include any classes, only import with 'use' and actually use it. All classes has simple DSL, you never will need to instantiate manually with 'new'. For Seq you have Seq::seq(1, 2, 3, 4) or Seq::seq('a', 'b', 'c') and for Map you have Map::map_(array('key', 'value'), array('key', 'value')). Php has it's natural tuples: array. So instead of reinventing the wheel I used array syntax as the tuple substitute.

## Further planes

I'm planning to add Set classes (a set of classes that sort the 'internal' array by the key), Range class: something like 

```php
Range::from(1)->to(10) 
# or
Range::from('a')->to('z')
```
and return an Seq starting from 1 and finishing in 10 or a Seq starting from a and finishing in z. 
A functional abstract Controller class with methods to do the IO (a.k.a echo and print) in something similar to Scala PlayFramework 
```scala
  def hi = Action(Ok("Hi").as("application/json"))
```
If possible I will try to wrap PHP Curl and Stream function in a fancy OO style
