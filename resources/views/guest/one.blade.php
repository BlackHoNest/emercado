<?php
    use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    $global = new GlobalDeclare();
    $id = $id ?? null;
?>

@push('scripts')
   <script src = "{{asset('storage/js/action.js')}}"></script>
   <script src = "{{asset('storage/js/login.js')}}"></script>
   <script src = "{{asset('storage/js/buyer.js')}}"></script>
@endpush

<x-app-layout  :assets="$assets ?? []">
    <div class="row">
        <div class="col-lg-2 col-sm-12">
            <div class = "card">
                <div class="row m-2">
                    <h2 class="h6 pb-2 border-bottom">Categories</h2>
                   
                    <ul class="list-unstyled mb-0">
                        @foreach($categories as $cat)
                        <li class="mt-2">
                        <a href = "#">{{$cat->description}}</a>
                        </li>
                        @endforeach
                    </ul>
                   
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-sm-12" <?=($global->isMobile()?'style = "margin-left: 10px; width: 97%"':'')?>>
            <div class = "card">    
                <div class="row mt-3">
                    <div class="col-lg-4 col-sm-12">

                        @if (count($posted->images) <= 0)
                            <div class = "img-wrapper">
                                <img class = "img-responsive" width = "100%" src = "{{asset('images/no-photo.jpg')}}">
                            </div>
                        @else
                        <div class="bd-example">
                        
                            <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-indicators">
                                    <?php
                                        $ctr = 0;
                                    ?>
                                    @foreach($posted->images as $img)
                                    <?php
                                        $active = "";
                                        if ($ctr == 0){
                                            $active = "active";
                                        }
                                    ?>
                                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{$ctr}}" class="{{$active }}" aria-label="Slide 1"></button>
                                    <?php
                                        $ctr++;
                                    ?>
                                    @endforeach
                                </div>
                                <div class="carousel-inner">
                                    <?php
                                        $ctr = 0;
                                    ?>
                                    @foreach($posted->images as $img)
                                    <?php
                                        $active = "";
                                        if ($ctr == 0){
                                            $active = "active";
                                        }
                                    ?>
                                    
                                    <div class="carousel-item {{$active}}" style = "height: 400px">
                                        <img src = "{{asset('storage/post/'.$posted->SellerID.'/size500/'.$img->FileName)}}">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5></h5>
                                            <p></p>
                                        </div>
                                        
                                    </div>
                                
                                    <?php
                                        $ctr++;
                                    ?>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="col-lg-8 col-sm-12">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="card-title">{{$posted->Title}}</h5>
                                <label class = 'mt-2'>Category: {!! '<a href = "#" class = "link text-primary">'.$posted->producttype->description .'</a> / <a href = "#" class = "link text-primary">' . $posted->kind->description .'</a>' !!}</span></label>
                                <div>
                                    <a href = "#" class = "" data-share = "{{$app->make('url')->to('/').'/share/product/'.$posted->slug_name}}"  data-bs-toggle="modal" data-bs-target="#modalshare">
                                        <i class = 'fa fa-share-alt text-danger' style = "margin-right: 10px"></i>
                                    </a>
                                    
                                    @if (auth()->check() && auth()->user()->user_type == "buyer")
                                        @if (empty($posted->fav))
                                        <a href = "#"  style = "margin-right: 10px" pid = "{{$aes->encrypt($posted->id, auth()->user()->secretkey)}}" class = "fav"><i class = 'far fa-heart text-success'></i></a>
                                        @else
                                        <a href = "#"  style = "margin-right: 10px" pid = "{{$aes->encrypt($posted->id, auth()->user()->secretkey)}}" class = "fav"><i class = 'fas fa-heart text-danger'></i></a>
                                        @endif
                                    
                                    @else
                                        @if (auth()->check() && auth()->user()->user_type == "seller")   
                                        @else
                                        <a href = "#"  style = "margin-right: 10px"  data-bs-toggle="modal" data-bs-target="#modallogin"><i class = 'fa fa-heart-o text-success'></i></a>
                                        @endif
                                    @endif

                                    @if (auth()->check() && auth()->user()->user_type == "buyer")
                                    <a href = "/chatify/{{$global->base64encode($posted->user2->id)}}" class = "badge bg-warning text-white"><i class = 'fa fa-send' style = "margin-right: 5px"></i> chat the seller</a>
                                    @else
                                        @if (auth()->check() && auth()->user()->user_type == "seller")   
                                        @else
                                        <a href = "#"class = "badge bg-warning text-white"  data-bs-toggle="modal" data-bs-target="#modallogin"><i class = 'fa fa-send' style = "margin-right: 5px"></i> chat the seller</a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="card-action">
                                
                            </div>
                        </div>

                        <div class="card-body">
                            <div><span class = 'h2 text-warning'>₱ {{number_format(str_replace(',','',$posted->Amount),2,'.',',')}} </span> /{{$posted->unit->UnitName}}</div>
                            <div class = ''>
                                @if ($posted->StockAvailable == 1)
                                    <span class = 'text-success'>Stocks are available</span><br>
                                    <span class = 'text-dark'>Remaining: {{$posted->Stocks . ' ' . $posted->unit->UnitName}}</span>
                                @else
                                <span class = 'text-danger'>Stocks will be available on {{date('F, Y', strtotime($posted->AvailableDate))}}</span><br>
                                    <span class = 'text-dark'>Target yield: {{$posted->Stocks . ' ' . $posted->unit->UnitName}}</span>
                                @endif

                                <div class = "mt-2"><i class = "fas fa-store"></i> <a href = "#" class = "link text-primary">{{$posted->user2->name}}</a></div>
                                <div style = "margin-left: 3px" class = "mb-2"><i class = "fas fa-map-marker-alt"></i>&nbsp;&nbsp;<a href = "#" class = "link text-primary">{{ucwords(strtolower($posted->seller->Barangay->brgyDesc))}}</a>, <a href = "#" class = "link text-primary">{{ucwords(strtolower($posted->seller->Municipality->citymunDesc))}}</a>, <a href = "#" class = "link text-primary">{{ucwords(strtolower($posted->seller->Province->provDesc))}}</a></div>
                                
                                @if (auth()->check() && auth()->user()->user_type == "buyer")
                                <div class = "mt-3">
                                    <input type="button" value="-" id="subs" class=" btn-default pull-left" style="margin-right: 5px" />&nbsp;
                                    <input type="text" style="width: 50px;text-align: center; margin-right: 2px;" class="onlyNumber pull-left" id="QTY" value="1" name="QTY"/>&nbsp;
                                    <input type="button" value="+" id="adds" class=" btn-default" />
                                </div>
                               
                                <a sellerid = "{{$aes->encrypt($posted->SellerID, auth()->user()->secretkey)}}" postid = "{{$aes->encrypt($posted->id, auth()->user()->secretkey)}}" class = "mt-3 btn btn-warning text-white text-underline addtocart"><i class = "fa fa-cart-plus"></i> Add to cart</a>
                                @else
                                    @if (auth()->check() && auth()->user()->user_type == "seller")   
                                    @else
                                    <a href = "#" class = "mt-3 btn btn-warning text-white text-underline"  data-bs-toggle="modal" data-bs-target="#modallogin"><i class = "fa fa-cart-plus"></i> Add to cart</a>
                                    @endif
                                @endif
                                
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row m-3">
                    <div class="col-12 mt-3">
                        <h6 class = "mb-2">Product description of {{$posted->Title}}</h6>

                        <div class="d-flex justify-content-between">
                            <div class="header-title">
                                {!! $posted->Description !!}
                            </div>
                        </div>

                        
                        
                    </div>
                </div>
            
            </div>            
        </div>
    </div>
</x-app-layout>
