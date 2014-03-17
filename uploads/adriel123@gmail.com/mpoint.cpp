// file mpoint.cpp
// driver "biasa" untuk mentest point.cpp

#include "point.h"
#include <iostream>
using namespace std;

int main(){
	Point P= Point(1,2);
	Point * PtP= new Point(5,5);
	cout << "start.." << endl;
	P.PrintObj();
	PtP->PrintObj();
	return 0;
};
