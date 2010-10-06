<?
interface IParen1
{
	public function method1()
}

interface IParen2
{
	public function method2()
}

interface IHell
{
	public function demon()
}

class Parent1 implements IParen1
{
	public function method1()
	{
		...
	}
}

class Parent2 implements IParen2
{
	public function method2()
	{
		...
	}
}

class Parent2 implements IParen1,IParen2,IHell
{
	private $par1
	private $par2
	
	public function __construct()
	{
		$this->par1=new Parent1;
		$this->par2=new Parent2;		
	}
	
	public function method1()
	{
		$this->par1->method1();
	}
	
	public function method2()
	{
		$this->par2->method2();
	}
	
	public function demon()
	{
		...
	}
}

?>