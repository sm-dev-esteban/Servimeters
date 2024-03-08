<?php

namespace Model;

use Config\CRUD;

class CalendarModel extends CRUD
{
    const TABLE_CALENDAR = "Calendar";
    // const TABLE_CALENDAR_DISPLAY = "EventDisplay";

    public function __construct()
    {
        parent::__construct();

        if (!$this->tableManager->checkTableExists(self::TABLE_CALENDAR)) self::createTableCalendar();
    }

    public function createEvent(?array $data = null)
    {
        return ($this->create)(self::TABLE_CALENDAR, $data ?: $this->fullRequest);
    }

    public function readEvent(?string $condition = null, array $prepare = []): mixed
    {
        return ($this->read)(self::TABLE_CALENDAR, $condition ?: "1 = 1", null, $prepare);
    }

    public function updateEvent(?array $data = null, int $id)
    {
        return ($this->update)(self::TABLE_CALENDAR, $data ?: $this->fullRequest, "id = {$id}");
    }

    public function deleteEvent(string $condition)
    {
        return ($this->delete)(self::TABLE_CALENDAR, $condition);
    }

    /**
     * Remove empty columns
     * @param array $data
     * @return array
     */
    public function renderEvents(array $data): array
    {
        $newData = [];

        foreach ($data as $i => $columns) foreach ($columns as $key => $value)
            if ($value && !empty($value)) $newData[$i][$key] = $value;

        return $newData;
    }

    /**
     * Cree cada columna en base a la documentación de fullcalendar, casi no doy con los datos mucho tsexo
     * https://fullcalendar.io/docs/v5/event-object
     */
    public function createTableCalendar(): void
    {
        # Create table with default columns (id, fechaRegistro).
        $this->tableManager->createTable(self::TABLE_CALENDAR);

        # String. Events that share a groupId will be dragged and resized together automatically.
        $this->tableManager->createColumn(self::TABLE_CALENDAR, "[groupId]");

        # Boolean (true or false). Determines if the event is shown in the “all-day” section of relevant views. In addition, if true the time text is not displayed with the event.
        // $this->tableManager->createColumn(self::TABLE_CALENDAR, "[allDay]", "BOOLEAN DEFAULT FALSE");
        $this->tableManager->createColumn(self::TABLE_CALENDAR, "[allDay]", "BIT DEFAULT FALSE");

        # Date object that obeys the current timeZone. When an event begins.
        $this->tableManager->createColumn(self::TABLE_CALENDAR, "[start]", "DATETIME DEFAULT NULL");

        # Date object that obeys the current timeZone. When an event ends. It could be null if an end wasn’t specified.
        # Note: This value is exclusive. For example, an event with the end of 2018-09-03 will appear to span through 2018-09-02 but end before the start of 2018-09-03. See how events are are parsed from a plain object for further details.
        $this->tableManager->createColumn(self::TABLE_CALENDAR, "[end]", "DATETIME DEFAULT NULL");

        # An ISO8601 string representation of the start date. If the event is all-day, there will not be a time part.
        $this->tableManager->createColumn(
            self::TABLE_CALENDAR,
            "startStr",
            "DATE DEFAULT NULL"
        );

        # An ISO8601 string representation of the end date. If the event is all-day, there will not be a time part.
        $this->tableManager->createColumn(
            self::TABLE_CALENDAR,
            "endStr",
            "DATE DEFAULT NULL"
        );

        # String. The text that will appear on an event.
        $this->tableManager->createColumn(self::TABLE_CALENDAR, "[title]");
        $this->tableManager->createColumn(self::TABLE_CALENDAR, "[description]");

        # String. A URL that will be visited when this event is clicked by the user. For more information on controlling this behavior, see the eventClick callback.
        $this->tableManager->createColumn(self::TABLE_CALENDAR, "[url]");

        # An array of strings like [ 'myclass1', myclass2' ]. Determines which HTML classNames will be attached to the rendered event.
        $this->tableManager->createColumn(self::TABLE_CALENDAR, "[classNames]");

        # Boolean (true or false) or null. The value overriding the editable setting for this specific event.
        // $this->tableManager->createColumn(self::TABLE_CALENDAR, "[editable]", "BOOLEAN DEFAULT FALSE");
        $this->tableManager->createColumn(self::TABLE_CALENDAR, "[editable]", "BIT DEFAULT FALSE");

        # Boolean (true or false) or null. The value overriding the eventStartEditable setting for this specific event.
        // $this->tableManager->createColumn(self::TABLE_CALENDAR, "[startEditable]", "BOOLEAN DEFAULT FALSE");
        $this->tableManager->createColumn(self::TABLE_CALENDAR, "[startEditable]", "BIT DEFAULT FALSE");

        # Boolean (true or false) or null. The value overriding the eventDurationEditable setting for this specific event.
        // $this->tableManager->createColumn(self::TABLE_CALENDAR, "[durationEditable]", "BOOLEAN DEFAULT FALSE");
        $this->tableManager->createColumn(self::TABLE_CALENDAR, "[durationEditable]", "BIT DEFAULT FALSE");

        # Boolean (true or false) or null. The value overriding the eventResourceEditable setting for this specific event.
        // $this->tableManager->createColumn(self::TABLE_CALENDAR, "[resourceEditable]", "BOOLEAN DEFAULT FALSE");
        $this->tableManager->createColumn(self::TABLE_CALENDAR, "[resourceEditable]", "BIT DEFAULT FALSE");

        # No sé cómo funciona, me perdí en la documentación, así que lo omitiré por ahora.

        # The rendering type of this event. Can be 'auto', 'block', 'list-item', 'background', 'inverse-background', or 'none'. See eventDisplay.
        // $this->tableManager->createColumn( self::TABLE_CALENDAR, "id_display","int default 1");

        // if ($this->tableManager->checkTableExists( self::TABLE_CALENDAR_DISPLAY)) {
        //     $this->tableManager->createTable( self::TABLE_CALENDAR_DISPLAY);

        //     $displayEvents = [
        //         ["display" => "auto"],
        //         ["display" => "block"],
        //         ["display" => "list-item"],
        //         ["display" => "background"],
        //         ["display" => "inverse-background"],
        //         ["display" => "none"]
        //     ];

        //     foreach ($displayEvents as $event) self::prepare(self::TABLE_CALENDAR_DISPLAY, ["data" => $event])->insert();
        //     $this->tableManager->addForeignKey( [self::TABLE_CALENDAR => self::TABLE_CALENDAR_DISPLAY], ["id_display" => "id"]);
        // }

        # The value overriding the eventOverlap setting for this specific event. If false, prevents this event from being dragged/resized over other events. Also prevents other events from being dragged/resized over this event. Does not accept a function.
        $this->tableManager->createColumn(self::TABLE_CALENDAR, "[overlap]");

        # The eventConstraint override for this specific event.
        $this->tableManager->createColumn(self::TABLE_CALENDAR, "[constraint]");

        # The eventBackgroundColor override for this specific event.
        $this->tableManager->createColumn(self::TABLE_CALENDAR, "[backgroundColor]");

        # The eventBorderColor override for this specific event.
        $this->tableManager->createColumn(self::TABLE_CALENDAR, "[borderColor]");

        # The eventTextColor override for this specific event.
        $this->tableManager->createColumn(self::TABLE_CALENDAR, "[textColor]");

        # A plain object holding miscellaneous other properties specified during parsing. Receives properties in the explicitly given extendedProps hash as well as other non-standard properties.
        $this->tableManager->createColumn(self::TABLE_CALENDAR, "[extendedProps]", "JSON DEFAULT NULL");

        # A reference to the Event Source this event came from. If the event was added dynamically via addEvent, and the source parameter was not specified, this value will be null.
        $this->tableManager->createColumn(self::TABLE_CALENDAR, "[source]");
    }
}
