@extends('layouts.app')

@section('content')
<div id="app">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="card-header">
                        <h3 class="text-center text-primary">{{__('Category management')}}<//h3>
                    </div>

                    <div class="card-body">
                        @if($categories->count())
                            <form action="" method="POST" id="categories-form" style="direction:ltr">
                                {{ csrf_field() }}
                                <table class="table table-striped" border="1">
                                    <thead style="background-color:#0074D9;color:white;">
                                    <tr>
                                        <td width="25%">{{__('Actions')}}<//td>
                                        <td width="30%">{{__('CP Score')}}<//td>
                                        <td width="30%">{{__('Category')}}<//td>
                                        <td width="15%">#</td>
                                    </tr>
                                    </thead>
                                </table>

                                @php $categoryNumber = 1; @endphp
                                @foreach($categories as $category)
                                    <table class="table" id="category_table" border="1">
                                        <tbody>
                                            <tr style="background-color: #AAAAAA;">
                                                <td style="width:25%;">
                                                    <a href="" style="color:black;" class="delete-item" data-action="category[delete][{{$category->id}}]">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                    <a style="color:black;" class="edit-item" data-action="category[edit][{{$category->id}}]" href="">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <label class="radioFork">
                                                        <input type="radio" name="fork" value="{{$category->id}}" {{ (isset($category->fork) && $category->fork ==1) ? 'checked' : '' }}>
                                                        <i class="fa fa-cutlery fa-sm text-center"></i>
                                                    </label>
                                                </td>
                                                <td style="width:30%;">
                                                    <span class="item-score category-total-score">{{ $category->score}}</span>
                                                </td>
                                                <td style="width:30%;" id="{{$category->id}}">
                                                    <a style="cursor:pointer;" class="item-name">{{ $category->name}}</a>
                                                </td>
                                                <td style="width:15%;">
                                                    <span>{{ $categoryNumber}}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    @if(isset($category->paragraphs))
                                        @php $counter = 1; @endphp
                                        <table class="table category-paragraphs" border="1px" @if (!$category->paragraphs->contains(request()->input('paragraph_id'))) hidden="hidden" @endif id="category_paragraphs_{{$category->id}}">
                                            <tbody data-category-id="{{$category->id}}">
                                                @foreach($category->paragraphs as $paragraph)
                                                    @php $number = $categoryNumber . "." . $counter; @endphp
                                                    <tr>
                                                        <td width="13%">
                                                            <a href="" style="color:black;" class="delete-item" data-action="paragraph[delete][{{$paragraph->id}}]"><i class="fa fa-trash"></i></a>
                                                            <a style="color:black;" class="edit-item" data-action="paragraph[edit][{{$paragraph->id}}]"><i class="fa fa-edit"></i></a>
                                                        </td>
                                                        <td width="12%"><span class="item-type">{{ __($paragraph->type) }}</span></td>
                                                        <td width="30%"><span class="item-score">{{ $paragraph->score }}</span></td>
                                                        <td width="30%"><a class="item-name" href="{{ action('ParagraphController@edit', $paragraph->id) }}">{{ $paragraph->name }}</a></td>
                                                        <td width="15%"><span>{{ $number }}</span></td>
                                                        @php $counter++ @endphp
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td width="13%"><a href="" style="color: black;" class="paragraph-add"><i class="fa fa-plus-circle"></i></a></td>
                                                    <td width="12%" class="item-add-type"></td>
                                                    <td width="30%" class="item-add-score"></td>
                                                    <td width="30%" class="item-add-name"></td>
                                                    <td width="15%"><span class="item-add-number">{{ $categoryNumber.'.'.$counter }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td width="13%">&nbsp;</td>
                                                    <td width="12%">&nbsp;</td>
                                                    <td width="30%">
                                                        @php $newVar = 0 @endphp
                                                        @foreach($category->paragraphs as $paragraph2)
                                                            @php $newVar+= $paragraph2['score'] @endphp
                                                        @endforeach
                                                        <span class="paragraph-total-score" style="{{ $newVar != 100 ? 'color:red' : ''}}">{{ $newVar }}%</span>
                                                    </td>
                                                    <td width="30%">&nbsp;</td>
                                                    <td width="15%"><b>{{__('Total')}}</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endif
                                    @php $categoryNumber++ @endphp
                                @endforeach
                                <table class="table table-striped" border="1">
                                    <tr>
                                        <td width="25%"><a href="" style="color: black;" class="category-add"><i class="fa fa-plus-circle"></i></a></td>
                                        <td width="30%" class="item-add-score"></td>
                                        <td width="30%" class="item-add-name"></td>
                                        <td width="15%"><span class="item-add-number">{{ $categoryNumber }}</span></td>
                                    </tr>
                                    <tr>
                                        <td width="25%"></td>
                                        <td width="30%"><span id="categories-total" style="{{ $categoriesTotalScore != 100 ? 'color:red' : ''}}">{{ $categoriesTotalScore }}%</span></td>
                                        <td width="30%"></td>
                                        <td width="15%"><span>{{__('Total')}}</span></td>
                                    </tr>
                                </table>
                                <div style="margin-top: 25px;">
                                    <button class="btn btn-success btn-block" id="add_new_category">
                                        <i class="fa fa-save"></i> {{__('Save')}}
                                    </button>
                                </div>
                            </form>
                        @else
                            <h3 class="text-center">{{__('No data in categories table')}}</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    [type=radio] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    [type=radio] + i {
        cursor: pointer;
    }

    [type=radio]:checked + i {
        background-color: black;
        color: white;
        border: 3px solid black;
        -webkit-text-stroke-width: 1px;
        -webkit-text-stroke-color: black;
    }

    .item-add-number {
        display: block !important;
    }

    /*.data-row .item-add-score:after,*/
    .item-score:after {
        content: '%';
        font-weight: bold;
    }

    .data-row-active .item-add-score:after,
    .data-row-active .item-score:after {
        content: '';
    }

    .item-name {
        /* font-weight: bold; */
        color: #3490dc !important;
    }

    .table {
        margin-bottom: 0px;
    }

    .data-row p {
        margin: 0;
    }

    .data-row input,
    .data-row select,
    .data-row-active p,
    .data-row-active b,
    .data-row-active span,
    .data-row-active .item-name {
        display: none;
    }

    .data-row-active input,
    .data-row-active select {
        display: block;
    }

    .new-paragraphs
</style>
<script>
$(document).ready(function () {
    var new_category_index = 0, new_paragraph_index = 0;

    $('#category_table td').click(function () {
        var attr = $('#category_paragraphs_' + this.id).attr('hidden');
        if (attr === undefined) {
            $('#category_paragraphs_' + this.id).attr('hidden', 'hidden');
        }
        if (attr == 'hidden') {
            $('#category_paragraphs_' + this.id).removeAttr('hidden');
        }

        var attr = $('#add_category_paragraphs_' + this.id).attr('hidden');
        if (attr === undefined) {
            $('#add_category_paragraphs_' + this.id).attr('hidden', 'hidden');
        }
        if (attr == 'hidden') {
            $('#add_category_paragraphs_' + this.id).removeAttr('hidden');
        }
    });

    $(document).on('keyup', '.paragraph-new-score-input', updateCategoryParagraphsTotal);

    $(document).on('click', '.category-add, .paragraph-add', function (e) {
        e.preventDefault();
        var icon = $(this).children(),
            tr = $(this).parent().parent(),
            td_score = tr.find('.item-add-score'),
            td_name = tr.find('.item-add-name'),
            td_type = tr.find('.item-add-type'),
            number = tr.find('.item-add-number').html(),
            new_row_action;

        if (!tr.hasClass('data-row')) {
            tr.addClass('data-row');
            if ($(this).hasClass('category-add')) {
                td_score.append(`<span class="category-total-score"></span><input type="number" name="category[new][${new_category_index}][score]">`);
                td_name.append(`<span></span><input type="text" name="category[new][${new_category_index}][name]">`);
            } else {
                var category_id = $(this).parent().parent().parent().data('category-id');
                td_type.append(`<span class="item-type"></span>
                                <select name="paragraph[new][${category_id}][${new_paragraph_index}][type]">
                                    <option selected value="normal">{{__('normal')}}</option>
                                    <option value="severe">{{__('severe')}}</option>
                                    <option value="severe">{{__('severe')}}</option>
                                </select>`);
                td_score.append(`<span class="item-score"></span><input type="number" class="paragraph-new-score-input" name="paragraph[new][${category_id}][${new_paragraph_index}][score]">`);
                td_name.append(`<b></span><input type="text" name="paragraph[new][${category_id}][${new_paragraph_index}][name]">`);
            }
            td_score.find('input').on('keyup change', function () {
                updateCategoriesTotal();
                $(this).prev().html($(this).val());
            })
            td_name.find('input').on('keyup change', function () {
                $(this).prev().html($(this).val());
            })
        }

        if ($(this).hasClass('category-add')) {
            tr.css('background', ' #AAAAAA');
            number = parseInt(number);
            new_row_action = 'category';
            number++;
            new_category_index++;
        } else {
            number = parseFloat(number);
            new_row_action = 'paragraph';
            number = (number * 10 + 1) / 10;
            new_paragraph_index++;
        }

        tr.attr('data-number', number);
        tr.attr('data-type', new_row_action);

        $(this).parent().html(`
            <a style="color:black; cursor: pointer;" onclick="deleteCategoryParagraph(${number}, '${new_row_action}');"><i class="fa fa-trash"></i></a>
        `);

        tr.after(`<tr>
            <td width="13%"><a href="" style="color: black;" class="${new_row_action}-add"><i class="fa fa-plus-circle"></i></a></td>
            <td width="12%" class="item-add-type"> </td>
            <td width="30%" class="item-add-score"> </td>
            <td width="30%" class="item-add-name"> </td>
            <td width="15%"><span class="item-add-number">${number}</span></td>
        </tr>`)

        if ($(this).hasClass('category-add')) {
            tr.on('click', function (e) {
                if (e.target.tagName != 'INPUT') {
                    $(this).next().toggle();
                }
            })
            tr.after(`<tr><td colspan="4" style="padding: 0px;"><table class="table category-paragraphs new-paragraphs" border="1px">
                                        <tbody data-category-id="new-${new_category_index - 1}">

                                                                                    <tr>
            <td width="12%"><a href="" style="color: black;" class="paragraph-add"><i class="fa fa-plus-circle"></i></a></td>
            <td width="34%" class="item-add-score"> </td>
            <td width="34%" class="item-add-name"> </td>
            <td width="20%"><span class="item-add-number">${number - 1}.1</span></td>
            </tr>
                                        <tr>
                                                <td width="12%">&nbsp;</td>
                                            <td width="34%">
                                            <span class="paragraph-total-score">0%</span>
                                            </td>
                                            <td width="34%">&nbsp;</td>
                                            <td width="20%"><b>Total</span></td>
                                        </tr>
                                                                        </tbody>
                        </table></td></tr>`);
        }
        tr.addClass('data-row-active');
    });

    $(document).on('click', '.edit-item', function (e) {
        e.preventDefault();
        var tr = $(this).parent().parent(),
            action = $(this).data('action'),
            type = tr.find('.item-type'),
            name = tr.find('.item-name'),
            score = tr.find('.item-score');

        if (!tr.hasClass('data-row')) {
            tr.addClass('data-row');
            if(type.length){
                type.after(`<select name="${action}[type]">
                        <option ${type.html() == 'normal' ? 'selected' : ''} value="normal">{{__('normal')}}</option>
                        <option ${type.html() == 'severe' ? 'selected' : ''} value="severe">{{__('severe')}}</option>
                    </select>`)
            }
            name.after(`<input type="text" name="${action}[name]" onkeyup="$(this).prev().html($(this).val());" value="${name.html()}">`);
            score.after(`<input type="number" name="${action}[score]" onkeyup="$(this).prev().html($(this).val()); updateCategoriesTotal(); updateCategoryParagraphsTotal()" value="${parseInt(score.html())}">`);
        }

        if (tr.hasClass('data-row-active')) {
            // tr.removeClass('data-row-active');
            updateCategoriesTotal();
        } else {
            tr.addClass('data-row-active');
        }
        $(this).remove();
    })

    $('.delete-item').on('click', function (e) {
        e.preventDefault();
        var action = $(this).data('action');
        swal({
            title: "{{__('Are you sure you want to delete it?')}}",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "{{__('Yes')}}",
            cancelButtonText: "{{__('No')}}",
        }).then((result) => {
            if (result.value) {
                var tr = $(this).parent().parent(),
                    table = tr.parent().parent();

                if (table.attr('id') == 'category_table') {
                    table.next().remove();
                    table.remove();
                    updateCategoriesTotal()
                } else {
                    updateCategoryParagraphsTotal()
                    tr.remove();
                }
            }
        });

        $('#categories-form').append(`<input type="hidden" name="${action}" value="true">`);
    })

});

function updateCategoriesTotal() {
    var total = 0;
    $.each($('.category-total-score'), function (index, elem) {
        total += parseFloat(elem.innerHTML);
    });
    $('#categories-total').html(total + '%');
}

function updateCategoryParagraphsTotal() {
    $.each($('[data-category-id]'), function (indx, table) {
        var total = 0;

        $.each($(table).find('.item-score'), function (index, elem) {
            console.log(elem);
            total += parseFloat(elem.innerHTML);
        });

        $(table).find('.paragraph-total-score').html(total + '%');
    });
}

function deleteCategoryParagraph(number, type) {
    swal({
        title: "{{__('Are you sure you want to delete it?')}}",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "{{__('Yes')}}",
        cancelButtonText: "{{__('No')}}",
    }).then((result) => {
        if (result.value) {
            var tr = $('#categories-form tr[data-type="'+type+'"][data-number="'+number+'"]');
            if(type == 'category') {
                tr.next().remove();
            }
            tr.remove();
        }
    });
}

</script>
@stop
