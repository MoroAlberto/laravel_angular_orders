import {Component, OnInit, ViewChild, signal} from '@angular/core';
import {OrdersService} from '../services/orders.service';
import {ClarityModule} from "@clr/angular";
import {ClrDatagridSortOrder} from '@clr/angular';
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {ClrDatagridStringFilterInterface} from '@clr/angular';
import {DatePipe} from "@angular/common";
import {FormBuilder, FormGroup, Validators} from '@angular/forms';

class NameFilter implements ClrDatagridStringFilterInterface<any> {
  accepts(item: any, search: string): boolean {
    if (item && item.name && typeof item.name === 'string') {
      return item.name.toLowerCase().includes(search.toLowerCase());
    }
    return false;
  }
}

@Component({
  selector: 'app-orders',
  templateUrl: './orders.component.html',
  styleUrl: './orders.component.css',
  imports: [
    ClarityModule,
    FormsModule,
    DatePipe,
    ReactiveFormsModule
  ],
  standalone: true,
})

export class OrdersComponent implements OnInit {
  orders: any[] = [];
  search: string = '';
  date: string = '';
  descSort = ClrDatagridSortOrder.DESC;
  orderForm: FormGroup;
  showOrderModal = false;
  showRemoveModal = false;
  orderModalTitle = '';
  removeModalText = '';

  protected nameFilter = new NameFilter();

  constructor(private apiService: OrdersService, private formBuilder: FormBuilder) {
    this.orderForm = this.formBuilder.group({
      id: [''],
      name: ['', Validators.required],
      description: ['', Validators.required],
      date: ['', Validators.required]
    });
  }

  ngOnInit(): void {
    this.loadOrders();
  }

  loadOrders(): void {
    this.apiService.getOrders(this.search, this.date).subscribe(data => {
      this.orders = data;
    });
  }

  applyFilters(): void {
    this.loadOrders();
  }

  clearFilters() {
    this.search = '';
    this.date = '';
    this.loadOrders();
  }

  openToAdd(): void {
    this.orderModalTitle = 'New order';
    this.orderForm.reset();
    this.showOrderModal = true;
  }

  openToEdit(order: any): void {
    this.orderModalTitle = 'Edit order';
    const formattedDate = this.formatDate(order.date);
    this.orderForm.patchValue({ ...order, date: formattedDate });
    this.showOrderModal = true;
  }

  saveOrUpdateOrder(): void {
    if (this.orderForm.invalid) {
      this.orderForm.markAllAsTouched();
      return;
    }
    const formValue = { ...this.orderForm.value };
    formValue.date = this.formatDate(formValue.date);

    if (!this.orderForm.value.id) {
      // Create new order
      this.apiService.createOrder(formValue).subscribe(
        () => {
          this.loadOrders();
          this.showOrderModal = false;
        });
    } else {
      // Update existing order
      const orderId = this.orderForm.value.id;
      this.apiService.updateOrder(orderId, formValue).subscribe(() => {
        this.loadOrders();
        this.showOrderModal = false;
      });
    }
  }

  openToRemove(order: any): void {
    this.removeModalText = `Confirm removal of order "${order.description}" ?`;
    this.orderForm.patchValue(order);
    this.showRemoveModal = true;
  }

  deleteOrder(): void {
    this.apiService.deleteOrder(this.orderForm.value.id).subscribe(() => {
      this.loadOrders();
      this.showRemoveModal = false;
    });
  }

  private formatDate(date: string): string {
    const parts = date.split('/');
    return `${parts[2]}-${parts[0]}-${parts[1]}`;
  }
}
