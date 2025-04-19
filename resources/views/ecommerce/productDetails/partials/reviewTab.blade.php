<div class="product-details-reviews pb-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-info mt-half">
                    <ul class="nav nav-pills justify-content-center" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" id="nav_desctiption" data-bs-toggle="pill" data-bs-target="#tab_description" role="tab" aria-controls="tab_description" aria-selected="true">Description</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" id="nav_review" data-bs-toggle="pill" data-bs-target="#tab_review" role="tab" aria-controls="tab_review" aria-selected="false">Reviews ({{ $product->reviews->count() }})</button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab_description" role="tabpanel" aria-labelledby="nav_desctiption">
                            <p>{{$description}}</p>
                        </div>
                        <div class="tab-pane fade" id="tab_review" role="tabpanel" aria-labelledby="nav_review">
                            <div class="product-review">
                                
                                <form action="{{ route('ecommerce.makeReview', $product->id) }}" method="post" class="review-form mb-30">
                                    @csrf
                                    <h2>Write a review</h2>
                                    <div class="form-group row mb-3">
                                        <div class="col">
                                            <label class="col-form-label"><span class="text-danger">*</span> Your Review</label>
                                            <textarea class="form-control" name="review_text" required></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <div class="col">
                                            <label class="col-form-label"><span class="text-danger">*</span> Rating</label>
                                            &nbsp;&nbsp;&nbsp; Bad&nbsp;
                                            <input type="radio" value="1" name="rating">
                                            &nbsp;
                                            <input type="radio" value="2" name="rating">
                                            &nbsp;
                                            <input type="radio" value="3" name="rating">
                                            &nbsp;
                                            <input type="radio" value="4" name="rating">
                                            &nbsp;
                                            <input type="radio" value="5" name="rating" checked>
                                            &nbsp;Good
                                        </div>
                                    </div>
                                    <div class="buttons d-flex justify-content-end">
                                        <button class="btn-cart rev-btn" type="submit">Continue</button>
                                    </div>
                                </form>

                                @if($product->reviews->count() <= 10)
                                @foreach ($product->reviews as $review)
                                    <div class="customer-review">
                                        <table class="table table-striped table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td><strong>{{ @$review->user->name }}</strong></td>
                                                    <td class="text-end">{{\Carbon\Carbon::parse($review->created_at)->format('d/m/Y')}}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <p>{{ $review->review_text }}</p>
                                                        <div class="product-ratings">
                                                            <ul class="ratting d-flex mt-2">
                                                                @for ($i = 1; $i <= $review->rating; $i++)
                                                                    <li><i class="fa fa-star"></i></li>
                                                                @endfor
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                                @endif

                                @if($product->reviews->count() > 10)
                                <button class="btn-cart rev-btn" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">See More</button>
                                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="staticBackdropLabel">All reviews</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @foreach ($product->reviews as $review)
                                                <div class="customer-review">
                                                    <table class="table table-striped table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <td><strong>{{ @$review->user->name }}</strong></td>
                                                                <td class="text-end">{{\Carbon\Carbon::parse($review->created_at)->format('d/m/Y')}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <p>{{ $review->review_text }}</p>
                                                                    <div class="product-ratings">
                                                                        <ul class="ratting d-flex mt-2">
                                                                            @for ($i = 1; $i <= $review->rating; $i++)
                                                                                <li><i class="fa fa-star"></i></li>
                                                                            @endfor
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endforeach
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>