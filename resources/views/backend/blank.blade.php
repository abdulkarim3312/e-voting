@extends('backend.master')

@section('title', '')

@section('content')

        <div class="page-header">
            <h2 class="page-title">New Page</h2>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">New Page</li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Title</h3>
            </div>
            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 col-12 mb-5">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="floatingInput" placeholder="John Doe" aria-describedby="floatingInputHelp">
                            <label for="floatingInput">Name</label>
                        </div>
                    </div>

                    <div class="col-md-6 col-12 mb-5">
                        <div class="form-floating form-floating-outline">
                            <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                <option>Open this select menu</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <label for="floatingSelect">Works with selects</label>
                        </div>
                    </div>

                    <div class="col-md-6 col-12 mb-5">
                        <div class="input-group input-group-merge">
                            <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="basic-addon13" placeholder="john.doe" aria-label="Recipient's username" aria-describedby="basic-addon13">
                            <label for="basic-addon13">Recipient's username</label>
                            </div>
                            <span class="input-group-text">@example.com</span>
                        </div>
                    </div>

                    <div class="col-md-12 col-12 mb-5">
                        <label for="inputGroupFile01">Select File</label>
                       <div class="input-group">
                            <input type="file" class="form-control" id="inputGroupFile01" accept="image/*" multiple>
                        </div>
                    </div>

                    <div class="col-md-6 col-12 mb-5">
                        <small class="fw-medium d-block">Check boxes</small>
                        <div class="form-check form-check-inline mt-4">
                            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1" checked>
                            <label class="form-check-label" for="inlineCheckbox1">Check Box 1</label>
                        </div>
                        <div class="form-check form-check-inline mt-4">
                            <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option1">
                            <label class="form-check-label" for="inlineCheckbox2">Check Box 2</label>
                        </div>
                    </div>

                    <div class="col-md-6 col-12 mb-5">
                        <small class="fw-medium d-block">Inline Radio</small>
                        <div class="form-check form-check-inline mt-4">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" checked>
                            <label class="form-check-label" for="inlineRadio1">Radio 1</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                            <label class="form-check-label" for="inlineRadio2">Radio 2</label>
                        </div>
                    </div>



                </div>

            </div>
        </div>






        <hr>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Title</h3>
            </div>
            <div class="card-body">
                <table id="DataTable" class="table table-bordered text-nowrap mb-0 dataTable no-footer" >
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Handle</th>
                            <th scope="col">Handle</th>
                            <th scope="col">Handle</th>
                            <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                            <td>@mdo</td>
                            <td>@mdo</td>
                            <td>@mdo</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>







@endsection
