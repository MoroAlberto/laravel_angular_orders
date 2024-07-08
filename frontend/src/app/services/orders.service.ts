import {HttpClient, HttpParams} from '@angular/common/http';
import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class OrdersService {
  private apiUrl = 'http://localhost:8000/api/orders';

  constructor(private http: HttpClient) { }

  getOrders(search: string, date: string): Observable<any[]> {
    let params = new HttpParams();
    if (search) {
      params = params.set('search', search);
    }
    if (date) {
      const formattedDate = this.formatDate(date);
      params = params.set('date', formattedDate);
    }
    return this.http.get<any[]>(this.apiUrl, { params: params });
  }

  getOrder(id: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/${id}`);
  }

  createOrder(order: any): Observable<any> {
    return this.http.post(`${this.apiUrl}`, order);
  }

  updateOrder(id: number, order: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/${id}`, order);
  }

  deleteOrder(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`);
  }

  private formatDate(date: string): string {
    const parts = date.split('/');
    return `${parts[2]}-${parts[0]}-${parts[1]}`;
  }
}
