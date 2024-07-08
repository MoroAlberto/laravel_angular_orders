import { Component } from '@angular/core';
import {ClarityModule, ClrDatagridStringFilterInterface} from '@clr/angular';
import {FormsModule} from "@angular/forms";

@Component({
  selector: 'app-order-filter',
  templateUrl: './filter.component.html',
  standalone: true,
  imports: [
    ClarityModule,
    FormsModule
  ],
  styleUrl: './filter.component.css'
})
export class OrderFilterComponent implements ClrDatagridStringFilterInterface<any> {
    accepts(order: any, search: string): boolean {
      return order.name.toLowerCase().includes(search.toLowerCase());
    }
}
