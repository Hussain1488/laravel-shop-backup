<table>
    <thead>
        <tr>
            @isset($codes[0]->id)
                <th>شماره</th>
            @endisset

            @isset($codes[0]->date)
                <th>تاریخ</th>
            @endisset

            @isset($codes[0]->insta)
                <th>کد اینستا گرام</th>
            @endisset

            @isset($codes[0]->rubika)
                <th>کد روبیکا</th>
            @endisset

            @isset($codes[0]->eitaa)
                <th>کد ایتا</th>
            @endisset

        </tr>
    </thead>
    <tbody>
        @foreach ($codes as $code)
            <tr>
                @isset($code['id'])
                    <td>{{ $code->id }}</td>
                @endisset

                @isset($code['date'])
                    <td>{{ Jdate($code->date)->format('Y-m-d') }}</td>
                @endisset

                @isset($code['insta'])
                    <td>{{ $code->insta }}</td>
                @endisset

                @isset($code['rubika'])
                    <td>{{ $code->rubika }}</td>
                @endisset

                @isset($code['eitaa'])
                    <td>{{ $code->eitaa }}</td>
                @endisset
            </tr>
        @endforeach
    </tbody>
</table>
