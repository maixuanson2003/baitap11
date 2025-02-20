<?php
interface CollectFUnct
{
    public function after($value);
    public function afters($value,$s);

}
class Collection{
    public $items = [];
    public function __construct($items){
        $this->items = $items;
    }
    public function after($value,bool $strict=true){
        $index=0;
        if(is_callable($value)){
            foreach($this->items as $key=>$item){
                if($value($item,$key)){
                    return $this->items[$key+1] ?? null;
                }
            }

        }else{
            $index = array_search($value, $this->items, $strict);
            echo $index;
            if ($index !== false && isset($this->items[$index + 1])) {
                return $this->items[$index + 1];
            }
            return null;
        }

        return null;
    }
    public  function afters($value,bool $strict){
        $index = array_search($value, $this->items, $strict);

        if ($index !== false && isset($this->items[$index + 1])) {
            return $this->items[$index + 1];
        }
        return null;
    }
    public function Median($key="")
    {
        if(array_keys($this->items[0]) !== range(0, count($this->items) - 1)){
            $result=[];
            foreach ($this->items as $item){
                foreach ($item as $index => $value) {
                    if($index === $key) {
                        array_push($result, $item[$index]);
                    }
                }
            }

            print_r($result);
            $left=0;
            $right=count($this->items)-1;
            $middle = floor(($left + $right) / 2);
            if(count($result)%2==0){

                return ($result[$middle]+$result[$middle+1])/2;

            }
                return $result[$middle];


        }
        $left=0;
        $right=count($this->items)-1;
        $middle = floor(($left + $right) / 2);
        if(count($this->items)%2==0){

            return ($this->items[$middle]+$this->items[$middle+1])/2;

        }
        return $this->items[$middle];

    }
    public  function Before($value,bool $strict){
        if(is_callable($value)){
            foreach($this->items as $key=>$item){
                if($value($item,$key)){
                    return $this->items[$key-1] ?? null;
                }
            }
        }else{
            $index = array_search($value, $this->items, $strict);


            if ($index !== false && isset($this->items[$index - 1])) {
                return $this->items[$index -1];
            }
        }
        return null;
    }
    public  function All()
    {
        return $this->items;

    }
    public function map(callable $func)
    {
        $result = [];
        foreach($this->items as $key=>$item){
            array_push($result,$func($item,$key));
        }
        return collect($result);

    }
    public function mapSpread(callable $func)
    {


    }
    public function where($key,$val)
    {
        $result = [];
        foreach ($this->items as $item){
            foreach ($item as $index => $value) {
                if($index === $key && $value === $val ){
                    array_push($result,$item);
                }
            }
        }
        return new collection($result) ;

    }
    public function wherenull($key)
    {
        $result = [];
        foreach ($this->items as $item){
            foreach ($item as $index => $value) {
                if($index === $key && $value === null){
                    array_push($result,$item);

                }
            }
        }
        return new collection($result) ;

    }
    public function whereBetween($key,$val)
    {
        $result = [];
        foreach ($this->items as $item){
            foreach ($item as $index => $value) {
                if($index === $key && ($value >= $val[0]&&$value <= $val[1]) ){
                    array_push($result,$item);
                }
            }
        }
        return new collection($result) ;

    }
    public function filter(callable $func)
    {
        $result = [];
        foreach($this->items as $key=>$item){
            if($func($item,$key)){
                array_push($result,$item);
            }
        }
        return new Collection($result);

    }
    public function last($value)
    {
        if(is_callable($value)){
            $result=0;
            foreach($this->items as $key=>$item){
                if ($value($item,$key)){
                    $result=$item;
                }
            }
            return $result;
        }
        return null;
    }
    public function Combine(array $items){
        if(count($items)<count($this->items)){
            throw  new Exception("faild");
        }
        $result = [];
        for($i=0;$i<count($this->items);$i++){
            $result[$this->items[$i]] = $items[$i]  ;
        }
        return new Collection($result);
    }
    public function mapWithKeys($value){
        $reult=[];
        if(is_callable($value)){
            foreach($this->items as $key=>$item){
                array_push($reult,$value($item,$key));
            }
        }
        return new Collection($reult);
    }


}
function collect(array $items): Collection {
    return new Collection($items);
}
//$item=collect(["name","age"]);
//$collection=$item->combine(["son",20]);
//echo $collection->All();
//
//// 8
//
//// null
//print_r($collection->All());
//$collection = collect([
//    [
//        'name' => 'John',
//        'department' => 'Sales',
//        'email' => 'john@example.com',
//    ],
//    [
//        'name' => 'Jane',
//        'department' => 'Marketing',
//        'email' => 'jane@example.com',
//    ]
//]);
//
//$keyed = $collection->mapWithKeys(function (array $item, int $key) {
//    return [$item['department'] => $item['email']];
//});
//$median = collect([
//    ['foo' => 10],
//    ['foo' => 10],
//    ['foo' => 20],
//    ['foo' => 40]
//])->median('foo');
//echo $median;
echo collect([1, 2,1, 3, 4])->last(function (int $value, int $key) {
    return $value < 3;
});
$collection = collect([
    ['name' => 'Desk'],
    ['name' => null],
    ['name' => 'Bookcase'],
]);

$filtered = $collection->whereNull('name');

print_r($filtered->all());

?>