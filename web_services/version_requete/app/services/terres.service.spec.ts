import { TestBed } from '@angular/core/testing';

import { TerresService } from './terres.service';

describe('TerresService', () => {
  let service: TerresService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(TerresService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
