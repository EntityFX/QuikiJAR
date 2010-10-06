interface  IPerson
{ 
	void  SetFam (string  aFam);
    string  GetFam ( );
}

interface  IStudent  extends  IPerson
{ 
	void  SetOplata ( ); // ��������� ������ ������� ������ �� ��������
    int  GetOplata ( );
}
 
interface  ISotr  extends  IPerson
{ 
	void  SetZarplata ( );  // ��������� ������ ������� ��������
    int  GetZarplata ( );
}

abstract class  Person  implements  IPerson
{
	private  string  Fam;  
    public  void  SetFam (string  aFam) { Fam = aFam;}
    public  string  GetFam ( ) {return  Fam;}
}

class  Student  extends  Person  implements  IStudent
{
	private  int  Oplata; // �������� - ����� ������ �� ��������
    public  void  SetOplata ( ); { ��� ��� ������� ���������}
    public  int  GetOplata ( ) {return  Oplata;}
};

class  Sotr  extends  Person  implements  ISotr
{
	private  int  Zarplata;  // �������� � �������� ���������� 
    public  void  SetZarplata ( )  {��� ��� ������� �����������}
    public  int  GetZarplata ( )  {return  Zarplata;}
};

class  StudSotr  extends  Person  implements  IStudent, ISotr
{
	private  int  Oplata;
    private  int  Zarplata;  
    public  void  SetOplata ( ) { ��� ��� ���������-�����������}
    public  int  GetOplata ( ) {return  Oplata;}
    public  void  SetZarplata ( )  {��� ��� �����������-���������}
    public  int  GetZarplata ( )  {return  Zarplata;}
}
