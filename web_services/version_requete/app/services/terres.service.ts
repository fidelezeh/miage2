import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class TerresService {

  baseUrl = 'http://localhost:8081/web_services/terresGabonaises';

  constructor(private http : HttpClient) { } 

  getTerreByProvince() : Observable<any>
  {
    return this.http.get(this.baseUrl + '/api/terres/readTerreOccuper.php');
  }

  
  getProvinceInoccuper() : Observable<any>
  {
    return this.http.get(this.baseUrl + '/api/terres/readTerreInoccuper.php');
  }
 
  getTauxOccupationReserve() : Observable<any>
  {
    return this.http.get(this.baseUrl + '/api/terres/readTauxOccupationReserve.php');
  }

  getReserve() : Observable<any>
  {
    return this.http.get(this.baseUrl + '/api/reserves/read.php');
  }

  getProvinces() {
    return this.http.get(this.baseUrl);
  }

  getDepartement(){
    return this.http.get(this.baseUrl);
  }

  getCoffeeShops() : Observable<any>
  {
    return this.http.get(this.baseUrl + '/api/terres/read.php');
  }
}
