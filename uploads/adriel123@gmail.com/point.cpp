// File : point.cpp

#include "point.h"
#include <iostream>
using namespace std;

// method Get and Set
int Point::GetX(){
	return x;
}

int Point::GetY(){
	return y;
}

void Point::SetX(int NewX){
	x=NewX;
}

void Point::SetY(int NewY){
	y=NewY;
}

//ctor
Point::Point(){
	cout << "Point ctor" <<endl;
}

Point::Point(int Newx, int Newy){
	x=Newx;
	y=Newy;
	cout << "Point ctor 2" <<endl;
}

//cctor
Point::Point(const Point& p){
	x=p.x;
	y=p.y;
}

//Operator=
Point& Point::operator= (const Point& p){
	x=p.x;
	y=p.y;

	return *this;
}

//dtor
Point::~Point(){
	cout << "point dtor" << endl;
}

int Point::LT(Point P1, Point P2){
//true (bukan 0) jika P1<P2 : absis dan ordinat lebih kecil
//Current objek tidak dipakai
	return (P1.GetX() < P2.GetX()) && (P1.GetY() < P2.GetY());
}

int Point::operator<(Point P1){
//true jika P1< Current Object : absis dan ordinat lebih kecil
//Perhatikan Current_Object dipakai
	return( (P1.GetX() < x) && (P1.GetY() < y));
}

//Predikat lain
int Point::IsOrigin(){
	return (x==0 && y==0);
}

//method
void Point::mirror(){
	x=-x;
	y=-y;
}

Point Point::Mirrorof(){
	int tmpx = -x;
	int tmpy= -y;
	return (Point (tmpx, tmpy));
}

void Point::PrintObj(){
	cout << "P=( " << x << "," << y << ")" << endl;
}
