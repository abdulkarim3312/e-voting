@extends('backend.master')

@section('title', 'জোন যোগ করুন')

@section('styles')
<style>
/* Tag container */
#tagContainer {
    display: flex;
    flex-wrap: wrap;
    border: 1px solid #ccc;
    padding: 5px;
    border-radius: 5px;
    cursor: text;
    min-height: 45px;
    position: relative;
}

/* Container label */
#tagContainer label {
    width: 100%;
    margin-bottom: 5px;
    font-weight: 500;
    font-size: 14px;
}

/* Individual tag */
#tagContainer .tag {
    display: flex;
    align-items: center;
    background-color: #007bff;
    color: #fff;
    padding: 3px 8px;
    margin: 3px 5px 3px 0;
    border-radius: 3px;
    font-size: 14px;
}

/* Tag remove "×" button */
#tagContainer .tag span {
    margin-left: 5px;
    cursor: pointer;
    font-weight: bold;
}

/* Input inside tag container */
#tagContainer input {
    border: none;
    flex: 1;
    min-width: 150px;
    padding: 5px;
    outline: none;
    font-size: 14px;
}

/* Suggestions dropdown */
#suggestions {
    height: auto;
    max-height: 200px;
    overflow: hidden;
    overflow-y: scroll;
    background: #fff;
    color: blue;
}
/* Individual suggestion item */
#suggestions .suggestion-item {
    padding: 7px 10px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.2s;
}

/* Hover effect on suggestion */
#suggestions .suggestion-item:hover {
    background-color: #f0f0f0;
}

#selectedItem {
    padding: 20px;
    border-radius: 5px;
    border: 1px solid #fff;
    color: #fff;
}

#selectedItem .tag {
    background: #c8cfc8;
    padding: 5px;
    border-radius: 5px;
    position: relative;
    color: #000;
    margin-right: 15px;
    margin-bottom: 20px;
    display: inline-block;
}

#selectedItem .tag span {
    background: #ff0000;
    color: #fff;
    cursor: pointer;
    position: absolute;
    right: -7px;
    top: -7px;
    border-radius: 10px;
    height: 15px;
    width: 15px;
    text-align: center;
    line-height: 1;
}
</style>
@endsection


@section('content')

        <div class="page-header">
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">হোম</a></li>
                    <li class="breadcrumb-item active" aria-current="page">জোন</li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">জোন যোগ করুন</h3>
            </div>
            <div class="card-body">

                <form action="{{ route('zone.upload') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 col-12 mb-5">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="floatingInput" placeholder="" aria-describedby="floatingInputHelp" name="zoneName" required>
                                <label for="floatingInput">জোনের নাম</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-6 mb-5">
                            <div id="tagContainer">
                                <label for="floatingInput">জেলা গুলো</label>
                                <input type="text" class="form-control" id="tagInput" placeholder="Type...">
                            </div>
                            <div class="suggestions" id="suggestions"></div>
                        </div>
                        <div class="col-md-6 col-6 mb-5">
                            <div id="selectedItem">
                                 <center>কোনো জেলা সিলেক্টেড নেই</center> 
                            </div>
                            <input type="hidden" name="districts" id="districts">
                        </div>
                    </div>
                    <center>
                        <button type="submit" class="btn btn-primary btn-sm waves-effect waves-light"><i class="fas fa-save me-1"></i> যুক্ত করুন </button>
                    </center>
                </form>

            </div>
        </div>









@endsection


@section('scripts')
<script>
var suggestions = @json(collect($list)->pluck('name'));
let tags = [];

function renderTags(){
    // Clear previous tags in selectedItem
    $('#selectedItem').empty();

    tags.forEach(t => {
        let tagElem = $(`<span class="tag">${t}<span>×</span></span>`);
        $('#selectedItem').append(tagElem);
    });

    $('#districts').val(tags.join(',')); // Update hidden input
}

// Remove tag on × click
$('#selectedItem').on('click', '.tag span', function(){
    let text = $(this).parent().text().slice(0, -1);
    tags = tags.filter(t => t !== text);
    renderTags();
});

// Show filtered suggestions
$('#tagInput').on('input', function(){
    let inputVal = $(this).val().toLowerCase();
    let filtered = suggestions.filter(s => s.toLowerCase().includes(inputVal) && !tags.includes(s));
    
    let html = filtered.map(s => `<div class="suggestion-item">${s}</div>`).join('');
    $('#suggestions').html(html).toggle(!!html);
});

// Click suggestion to add
$('#suggestions').on('click', '.suggestion-item', function(){
    tags.push($(this).text());
    renderTags();
    $('#tagInput').val('');
    $('#suggestions').hide();
});

// Enter key to add
$('#tagInput').on('keydown', function(e){
    if(e.key === 'Enter' && $(this).val().trim() !== ''){
        e.preventDefault();
        let val = $(this).val().trim();
        if(!tags.includes(val)) tags.push(val);
        renderTags();
        $(this).val('');
        $('#suggestions').hide();
    }
});

// Click outside to hide suggestions
$(document).click(function(e){
    if(!$(e.target).closest('#tagContainer,#suggestions,#selectedItem').length){
        $('#suggestions').hide();
    }
});

</script>

@endsection
