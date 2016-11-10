<div class="pagination">
    {!! $data['default'] !!}
    <form>
        @foreach ($data['query'] as $name => $value)
            <input name="{{ $name }}" type="hidden" value="{{ $value }}">
        @endforeach
        <span>共{{ $data['total'] }}页， 到第</span>
        <input name="page" value="{{ $data['total'] }}">
        <span>页</span>
        <input type="submit" value="确定">
    </form>
</div>