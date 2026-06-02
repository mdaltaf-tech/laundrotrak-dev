<div class="dashboard-main-body">

    <div class="card">

        <div class="card-header">
            <h5>
                Service Categories
            </h5>
        </div>

        <div class="card-body">

            <table class="table">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Services</th>
                        <th>Sort</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($categories as $item)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $item->category_name }}
                        </td>

                        <td>
                            {{ $item->services_count }}
                        </td>

                        <td>
                            {{ $item->sort_order }}
                        </td>

                        <td>

                            @if($item->is_active)

                                Active

                            @else

                                Inactive

                            @endif

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>
