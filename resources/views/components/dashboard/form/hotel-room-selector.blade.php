<div class="form-group row">
    <label class="col-xl-3 col-md-4" for="hotel_id">Hotel</label>
    <div class="col-xl-8 col-md-7">
        <select class="custom-select w-100 form-control"
                name="hotel_id"
                id="hotel_id"
                data-url="{{ $dataUrl }}">
            <option value="">--Select Hotel--</option>
            @foreach($hotels as $hotel)
                <option value="{{ $hotel->id }}" @if(isset($selectedHotel) && $selectedHotel == $hotel->id) selected @endif>
                    {{ $hotel->name }}@if($hotel->city) - {{ $hotel->city }}@endif
                </option>
            @endforeach
        </select>
    </div>

    <label class="col-xl-3 col-md-4 mt-3" for="room_id">Room</label>
    <div class="col-xl-8 col-md-7 mt-3">
        <select class="custom-select w-100 form-control" name="room_id" id="room_id">
            <option value="">--Select Room--</option>
            @if(isset($rooms))
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" @if(isset($selectedRoom) && $selectedRoom == $room->id) selected @endif>
                        {{ $room->name }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
</div>
