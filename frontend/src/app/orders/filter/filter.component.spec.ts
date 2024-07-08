import { ComponentFixture, TestBed } from '@angular/core/testing';

import { OrderFilterComponent } from './filter.component';

describe('FilterComponent', () => {
  let component: OrderFilterComponent;
  let fixture: ComponentFixture<OrderFilterComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [OrderFilterComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(OrderFilterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
