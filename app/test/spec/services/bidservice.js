'use strict';

describe('Service: bidService', function () {

  // load the service's module
  beforeEach(module('mercatoApp'));

  // instantiate service
  var bidService;
  beforeEach(inject(function (_bidService_) {
    bidService = _bidService_;
  }));

  it('should do something', function () {
    expect(!!bidService).toBe(true);
  });

});
