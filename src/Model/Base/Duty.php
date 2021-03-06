<?php

namespace Perfumerlabs\Start\Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use App\Model\User;
use App\Model\UserQuery;
use Perfumerlabs\Start\Model\Activity as ChildActivity;
use Perfumerlabs\Start\Model\ActivityQuery as ChildActivityQuery;
use Perfumerlabs\Start\Model\Duty as ChildDuty;
use Perfumerlabs\Start\Model\DutyQuery as ChildDutyQuery;
use Perfumerlabs\Start\Model\RelatedTag as ChildRelatedTag;
use Perfumerlabs\Start\Model\RelatedTagQuery as ChildRelatedTagQuery;
use Perfumerlabs\Start\Model\Map\DutyTableMap;
use Perfumerlabs\Start\Model\Map\RelatedTagTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'duty' table.
 *
 *
 *
 * @package    propel.generator..Base
 */
abstract class Duty implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Perfumerlabs\\Start\\Model\\Map\\DutyTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the user_id field.
     *
     * @var        int
     */
    protected $user_id;

    /**
     * The value for the activity_id field.
     *
     * @var        int
     */
    protected $activity_id;

    /**
     * The value for the comment field.
     *
     * @var        string
     */
    protected $comment;

    /**
     * The value for the raised_at field.
     *
     * @var        DateTime
     */
    protected $raised_at;

    /**
     * The value for the picked_at field.
     *
     * @var        DateTime
     */
    protected $picked_at;

    /**
     * The value for the closed_at field.
     *
     * @var        DateTime
     */
    protected $closed_at;

    /**
     * The value for the validation_url field.
     *
     * @var        string
     */
    protected $validation_url;

    /**
     * The value for the iframe_url field.
     *
     * @var        string
     */
    protected $iframe_url;

    /**
     * The value for the description field.
     *
     * @var        string
     */
    protected $description;

    /**
     * The value for the code field.
     *
     * @var        string
     */
    protected $code;

    /**
     * The value for the created_at field.
     *
     * @var        DateTime
     */
    protected $created_at;

    /**
     * @var        ChildActivity
     */
    protected $aActivity;

    /**
     * @var        User
     */
    protected $aUser;

    /**
     * @var        ObjectCollection|ChildRelatedTag[] Collection to store aggregation of ChildRelatedTag objects.
     */
    protected $collRelatedTags;
    protected $collRelatedTagsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildRelatedTag[]
     */
    protected $relatedTagsScheduledForDeletion = null;

    /**
     * Initializes internal state of Perfumerlabs\Start\Model\Base\Duty object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Duty</code> instance.  If
     * <code>obj</code> is an instance of <code>Duty</code>, delegates to
     * <code>equals(Duty)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Duty The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [user_id] column value.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Get the [activity_id] column value.
     *
     * @return int
     */
    public function getActivityId()
    {
        return $this->activity_id;
    }

    /**
     * Get the [comment] column value.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Get the [optionally formatted] temporal [raised_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getRaisedAt($format = NULL)
    {
        if ($format === null) {
            return $this->raised_at;
        } else {
            return $this->raised_at instanceof \DateTimeInterface ? $this->raised_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [picked_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getPickedAt($format = NULL)
    {
        if ($format === null) {
            return $this->picked_at;
        } else {
            return $this->picked_at instanceof \DateTimeInterface ? $this->picked_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [closed_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getClosedAt($format = NULL)
    {
        if ($format === null) {
            return $this->closed_at;
        } else {
            return $this->closed_at instanceof \DateTimeInterface ? $this->closed_at->format($format) : null;
        }
    }

    /**
     * Get the [validation_url] column value.
     *
     * @return string
     */
    public function getValidationUrl()
    {
        return $this->validation_url;
    }

    /**
     * Get the [iframe_url] column value.
     *
     * @return string
     */
    public function getIframeUrl()
    {
        return $this->iframe_url;
    }

    /**
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the [code] column value.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTimeInterface ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Perfumerlabs\Start\Model\Duty The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[DutyTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [user_id] column.
     *
     * @param int $v new value
     * @return $this|\Perfumerlabs\Start\Model\Duty The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[DutyTableMap::COL_USER_ID] = true;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }

        return $this;
    } // setUserId()

    /**
     * Set the value of [activity_id] column.
     *
     * @param int $v new value
     * @return $this|\Perfumerlabs\Start\Model\Duty The current object (for fluent API support)
     */
    public function setActivityId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->activity_id !== $v) {
            $this->activity_id = $v;
            $this->modifiedColumns[DutyTableMap::COL_ACTIVITY_ID] = true;
        }

        if ($this->aActivity !== null && $this->aActivity->getId() !== $v) {
            $this->aActivity = null;
        }

        return $this;
    } // setActivityId()

    /**
     * Set the value of [comment] column.
     *
     * @param string $v new value
     * @return $this|\Perfumerlabs\Start\Model\Duty The current object (for fluent API support)
     */
    public function setComment($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->comment !== $v) {
            $this->comment = $v;
            $this->modifiedColumns[DutyTableMap::COL_COMMENT] = true;
        }

        return $this;
    } // setComment()

    /**
     * Sets the value of [raised_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Perfumerlabs\Start\Model\Duty The current object (for fluent API support)
     */
    public function setRaisedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->raised_at !== null || $dt !== null) {
            if ($this->raised_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->raised_at->format("Y-m-d H:i:s.u")) {
                $this->raised_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[DutyTableMap::COL_RAISED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setRaisedAt()

    /**
     * Sets the value of [picked_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Perfumerlabs\Start\Model\Duty The current object (for fluent API support)
     */
    public function setPickedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->picked_at !== null || $dt !== null) {
            if ($this->picked_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->picked_at->format("Y-m-d H:i:s.u")) {
                $this->picked_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[DutyTableMap::COL_PICKED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setPickedAt()

    /**
     * Sets the value of [closed_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Perfumerlabs\Start\Model\Duty The current object (for fluent API support)
     */
    public function setClosedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->closed_at !== null || $dt !== null) {
            if ($this->closed_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->closed_at->format("Y-m-d H:i:s.u")) {
                $this->closed_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[DutyTableMap::COL_CLOSED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setClosedAt()

    /**
     * Set the value of [validation_url] column.
     *
     * @param string $v new value
     * @return $this|\Perfumerlabs\Start\Model\Duty The current object (for fluent API support)
     */
    public function setValidationUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->validation_url !== $v) {
            $this->validation_url = $v;
            $this->modifiedColumns[DutyTableMap::COL_VALIDATION_URL] = true;
        }

        return $this;
    } // setValidationUrl()

    /**
     * Set the value of [iframe_url] column.
     *
     * @param string $v new value
     * @return $this|\Perfumerlabs\Start\Model\Duty The current object (for fluent API support)
     */
    public function setIframeUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->iframe_url !== $v) {
            $this->iframe_url = $v;
            $this->modifiedColumns[DutyTableMap::COL_IFRAME_URL] = true;
        }

        return $this;
    } // setIframeUrl()

    /**
     * Set the value of [description] column.
     *
     * @param string $v new value
     * @return $this|\Perfumerlabs\Start\Model\Duty The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[DutyTableMap::COL_DESCRIPTION] = true;
        }

        return $this;
    } // setDescription()

    /**
     * Set the value of [code] column.
     *
     * @param string $v new value
     * @return $this|\Perfumerlabs\Start\Model\Duty The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[DutyTableMap::COL_CODE] = true;
        }

        return $this;
    } // setCode()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\Perfumerlabs\Start\Model\Duty The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[DutyTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : DutyTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : DutyTableMap::translateFieldName('UserId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->user_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : DutyTableMap::translateFieldName('ActivityId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->activity_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : DutyTableMap::translateFieldName('Comment', TableMap::TYPE_PHPNAME, $indexType)];
            $this->comment = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : DutyTableMap::translateFieldName('RaisedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->raised_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : DutyTableMap::translateFieldName('PickedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->picked_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : DutyTableMap::translateFieldName('ClosedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->closed_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : DutyTableMap::translateFieldName('ValidationUrl', TableMap::TYPE_PHPNAME, $indexType)];
            $this->validation_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : DutyTableMap::translateFieldName('IframeUrl', TableMap::TYPE_PHPNAME, $indexType)];
            $this->iframe_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : DutyTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : DutyTableMap::translateFieldName('Code', TableMap::TYPE_PHPNAME, $indexType)];
            $this->code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : DutyTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 12; // 12 = DutyTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Perfumerlabs\\Start\\Model\\Duty'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
            $this->aUser = null;
        }
        if ($this->aActivity !== null && $this->activity_id !== $this->aActivity->getId()) {
            $this->aActivity = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DutyTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildDutyQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aActivity = null;
            $this->aUser = null;
            $this->collRelatedTags = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Duty::setDeleted()
     * @see Duty::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(DutyTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildDutyQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(DutyTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(DutyTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                DutyTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aActivity !== null) {
                if ($this->aActivity->isModified() || $this->aActivity->isNew()) {
                    $affectedRows += $this->aActivity->save($con);
                }
                $this->setActivity($this->aActivity);
            }

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->relatedTagsScheduledForDeletion !== null) {
                if (!$this->relatedTagsScheduledForDeletion->isEmpty()) {
                    \Perfumerlabs\Start\Model\RelatedTagQuery::create()
                        ->filterByPrimaryKeys($this->relatedTagsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->relatedTagsScheduledForDeletion = null;
                }
            }

            if ($this->collRelatedTags !== null) {
                foreach ($this->collRelatedTags as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[DutyTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . DutyTableMap::COL_ID . ')');
        }
        if (null === $this->id) {
            try {
                $dataFetcher = $con->query("SELECT nextval('duty_id_seq')");
                $this->id = (int) $dataFetcher->fetchColumn();
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', 0, $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(DutyTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(DutyTableMap::COL_USER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'user_id';
        }
        if ($this->isColumnModified(DutyTableMap::COL_ACTIVITY_ID)) {
            $modifiedColumns[':p' . $index++]  = 'activity_id';
        }
        if ($this->isColumnModified(DutyTableMap::COL_COMMENT)) {
            $modifiedColumns[':p' . $index++]  = 'comment';
        }
        if ($this->isColumnModified(DutyTableMap::COL_RAISED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'raised_at';
        }
        if ($this->isColumnModified(DutyTableMap::COL_PICKED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'picked_at';
        }
        if ($this->isColumnModified(DutyTableMap::COL_CLOSED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'closed_at';
        }
        if ($this->isColumnModified(DutyTableMap::COL_VALIDATION_URL)) {
            $modifiedColumns[':p' . $index++]  = 'validation_url';
        }
        if ($this->isColumnModified(DutyTableMap::COL_IFRAME_URL)) {
            $modifiedColumns[':p' . $index++]  = 'iframe_url';
        }
        if ($this->isColumnModified(DutyTableMap::COL_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'description';
        }
        if ($this->isColumnModified(DutyTableMap::COL_CODE)) {
            $modifiedColumns[':p' . $index++]  = 'code';
        }
        if ($this->isColumnModified(DutyTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }

        $sql = sprintf(
            'INSERT INTO duty (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'user_id':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
                        break;
                    case 'activity_id':
                        $stmt->bindValue($identifier, $this->activity_id, PDO::PARAM_INT);
                        break;
                    case 'comment':
                        $stmt->bindValue($identifier, $this->comment, PDO::PARAM_STR);
                        break;
                    case 'raised_at':
                        $stmt->bindValue($identifier, $this->raised_at ? $this->raised_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'picked_at':
                        $stmt->bindValue($identifier, $this->picked_at ? $this->picked_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'closed_at':
                        $stmt->bindValue($identifier, $this->closed_at ? $this->closed_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'validation_url':
                        $stmt->bindValue($identifier, $this->validation_url, PDO::PARAM_STR);
                        break;
                    case 'iframe_url':
                        $stmt->bindValue($identifier, $this->iframe_url, PDO::PARAM_STR);
                        break;
                    case 'description':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case 'code':
                        $stmt->bindValue($identifier, $this->code, PDO::PARAM_STR);
                        break;
                    case 'created_at':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = DutyTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getUserId();
                break;
            case 2:
                return $this->getActivityId();
                break;
            case 3:
                return $this->getComment();
                break;
            case 4:
                return $this->getRaisedAt();
                break;
            case 5:
                return $this->getPickedAt();
                break;
            case 6:
                return $this->getClosedAt();
                break;
            case 7:
                return $this->getValidationUrl();
                break;
            case 8:
                return $this->getIframeUrl();
                break;
            case 9:
                return $this->getDescription();
                break;
            case 10:
                return $this->getCode();
                break;
            case 11:
                return $this->getCreatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Duty'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Duty'][$this->hashCode()] = true;
        $keys = DutyTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUserId(),
            $keys[2] => $this->getActivityId(),
            $keys[3] => $this->getComment(),
            $keys[4] => $this->getRaisedAt(),
            $keys[5] => $this->getPickedAt(),
            $keys[6] => $this->getClosedAt(),
            $keys[7] => $this->getValidationUrl(),
            $keys[8] => $this->getIframeUrl(),
            $keys[9] => $this->getDescription(),
            $keys[10] => $this->getCode(),
            $keys[11] => $this->getCreatedAt(),
        );
        if ($result[$keys[4]] instanceof \DateTime) {
            $result[$keys[4]] = $result[$keys[4]]->format('c');
        }

        if ($result[$keys[5]] instanceof \DateTime) {
            $result[$keys[5]] = $result[$keys[5]]->format('c');
        }

        if ($result[$keys[6]] instanceof \DateTime) {
            $result[$keys[6]] = $result[$keys[6]]->format('c');
        }

        if ($result[$keys[11]] instanceof \DateTime) {
            $result[$keys[11]] = $result[$keys[11]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aActivity) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'activity';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'activity';
                        break;
                    default:
                        $key = 'Activity';
                }

                $result[$key] = $this->aActivity->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUser) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'user';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = '_user';
                        break;
                    default:
                        $key = 'User';
                }

                $result[$key] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collRelatedTags) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'relatedTags';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'related_tags';
                        break;
                    default:
                        $key = 'RelatedTags';
                }

                $result[$key] = $this->collRelatedTags->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Perfumerlabs\Start\Model\Duty
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = DutyTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Perfumerlabs\Start\Model\Duty
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setUserId($value);
                break;
            case 2:
                $this->setActivityId($value);
                break;
            case 3:
                $this->setComment($value);
                break;
            case 4:
                $this->setRaisedAt($value);
                break;
            case 5:
                $this->setPickedAt($value);
                break;
            case 6:
                $this->setClosedAt($value);
                break;
            case 7:
                $this->setValidationUrl($value);
                break;
            case 8:
                $this->setIframeUrl($value);
                break;
            case 9:
                $this->setDescription($value);
                break;
            case 10:
                $this->setCode($value);
                break;
            case 11:
                $this->setCreatedAt($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = DutyTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setUserId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setActivityId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setComment($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setRaisedAt($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setPickedAt($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setClosedAt($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setValidationUrl($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setIframeUrl($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setDescription($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setCode($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setCreatedAt($arr[$keys[11]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Perfumerlabs\Start\Model\Duty The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(DutyTableMap::DATABASE_NAME);

        if ($this->isColumnModified(DutyTableMap::COL_ID)) {
            $criteria->add(DutyTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(DutyTableMap::COL_USER_ID)) {
            $criteria->add(DutyTableMap::COL_USER_ID, $this->user_id);
        }
        if ($this->isColumnModified(DutyTableMap::COL_ACTIVITY_ID)) {
            $criteria->add(DutyTableMap::COL_ACTIVITY_ID, $this->activity_id);
        }
        if ($this->isColumnModified(DutyTableMap::COL_COMMENT)) {
            $criteria->add(DutyTableMap::COL_COMMENT, $this->comment);
        }
        if ($this->isColumnModified(DutyTableMap::COL_RAISED_AT)) {
            $criteria->add(DutyTableMap::COL_RAISED_AT, $this->raised_at);
        }
        if ($this->isColumnModified(DutyTableMap::COL_PICKED_AT)) {
            $criteria->add(DutyTableMap::COL_PICKED_AT, $this->picked_at);
        }
        if ($this->isColumnModified(DutyTableMap::COL_CLOSED_AT)) {
            $criteria->add(DutyTableMap::COL_CLOSED_AT, $this->closed_at);
        }
        if ($this->isColumnModified(DutyTableMap::COL_VALIDATION_URL)) {
            $criteria->add(DutyTableMap::COL_VALIDATION_URL, $this->validation_url);
        }
        if ($this->isColumnModified(DutyTableMap::COL_IFRAME_URL)) {
            $criteria->add(DutyTableMap::COL_IFRAME_URL, $this->iframe_url);
        }
        if ($this->isColumnModified(DutyTableMap::COL_DESCRIPTION)) {
            $criteria->add(DutyTableMap::COL_DESCRIPTION, $this->description);
        }
        if ($this->isColumnModified(DutyTableMap::COL_CODE)) {
            $criteria->add(DutyTableMap::COL_CODE, $this->code);
        }
        if ($this->isColumnModified(DutyTableMap::COL_CREATED_AT)) {
            $criteria->add(DutyTableMap::COL_CREATED_AT, $this->created_at);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildDutyQuery::create();
        $criteria->add(DutyTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Perfumerlabs\Start\Model\Duty (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUserId($this->getUserId());
        $copyObj->setActivityId($this->getActivityId());
        $copyObj->setComment($this->getComment());
        $copyObj->setRaisedAt($this->getRaisedAt());
        $copyObj->setPickedAt($this->getPickedAt());
        $copyObj->setClosedAt($this->getClosedAt());
        $copyObj->setValidationUrl($this->getValidationUrl());
        $copyObj->setIframeUrl($this->getIframeUrl());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setCode($this->getCode());
        $copyObj->setCreatedAt($this->getCreatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getRelatedTags() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addRelatedTag($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Perfumerlabs\Start\Model\Duty Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildActivity object.
     *
     * @param  ChildActivity $v
     * @return $this|\Perfumerlabs\Start\Model\Duty The current object (for fluent API support)
     * @throws PropelException
     */
    public function setActivity(ChildActivity $v = null)
    {
        if ($v === null) {
            $this->setActivityId(NULL);
        } else {
            $this->setActivityId($v->getId());
        }

        $this->aActivity = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildActivity object, it will not be re-added.
        if ($v !== null) {
            $v->addDuty($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildActivity object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildActivity The associated ChildActivity object.
     * @throws PropelException
     */
    public function getActivity(ConnectionInterface $con = null)
    {
        if ($this->aActivity === null && ($this->activity_id !== null)) {
            $this->aActivity = ChildActivityQuery::create()->findPk($this->activity_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aActivity->addDuties($this);
             */
        }

        return $this->aActivity;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param  User $v
     * @return $this|\Perfumerlabs\Start\Model\Duty The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(User $v = null)
    {
        if ($v === null) {
            $this->setUserId(NULL);
        } else {
            $this->setUserId($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addDuty($this);
        }


        return $this;
    }


    /**
     * Get the associated User object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return User The associated User object.
     * @throws PropelException
     */
    public function getUser(ConnectionInterface $con = null)
    {
        if ($this->aUser === null && ($this->user_id !== null)) {
            $this->aUser = UserQuery::create()->findPk($this->user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addDuties($this);
             */
        }

        return $this->aUser;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('RelatedTag' == $relationName) {
            return $this->initRelatedTags();
        }
    }

    /**
     * Clears out the collRelatedTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addRelatedTags()
     */
    public function clearRelatedTags()
    {
        $this->collRelatedTags = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collRelatedTags collection loaded partially.
     */
    public function resetPartialRelatedTags($v = true)
    {
        $this->collRelatedTagsPartial = $v;
    }

    /**
     * Initializes the collRelatedTags collection.
     *
     * By default this just sets the collRelatedTags collection to an empty array (like clearcollRelatedTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initRelatedTags($overrideExisting = true)
    {
        if (null !== $this->collRelatedTags && !$overrideExisting) {
            return;
        }

        $collectionClassName = RelatedTagTableMap::getTableMap()->getCollectionClassName();

        $this->collRelatedTags = new $collectionClassName;
        $this->collRelatedTags->setModel('\Perfumerlabs\Start\Model\RelatedTag');
    }

    /**
     * Gets an array of ChildRelatedTag objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildDuty is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildRelatedTag[] List of ChildRelatedTag objects
     * @throws PropelException
     */
    public function getRelatedTags(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collRelatedTagsPartial && !$this->isNew();
        if (null === $this->collRelatedTags || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collRelatedTags) {
                // return empty collection
                $this->initRelatedTags();
            } else {
                $collRelatedTags = ChildRelatedTagQuery::create(null, $criteria)
                    ->filterByDuty($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collRelatedTagsPartial && count($collRelatedTags)) {
                        $this->initRelatedTags(false);

                        foreach ($collRelatedTags as $obj) {
                            if (false == $this->collRelatedTags->contains($obj)) {
                                $this->collRelatedTags->append($obj);
                            }
                        }

                        $this->collRelatedTagsPartial = true;
                    }

                    return $collRelatedTags;
                }

                if ($partial && $this->collRelatedTags) {
                    foreach ($this->collRelatedTags as $obj) {
                        if ($obj->isNew()) {
                            $collRelatedTags[] = $obj;
                        }
                    }
                }

                $this->collRelatedTags = $collRelatedTags;
                $this->collRelatedTagsPartial = false;
            }
        }

        return $this->collRelatedTags;
    }

    /**
     * Sets a collection of ChildRelatedTag objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $relatedTags A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildDuty The current object (for fluent API support)
     */
    public function setRelatedTags(Collection $relatedTags, ConnectionInterface $con = null)
    {
        /** @var ChildRelatedTag[] $relatedTagsToDelete */
        $relatedTagsToDelete = $this->getRelatedTags(new Criteria(), $con)->diff($relatedTags);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->relatedTagsScheduledForDeletion = clone $relatedTagsToDelete;

        foreach ($relatedTagsToDelete as $relatedTagRemoved) {
            $relatedTagRemoved->setDuty(null);
        }

        $this->collRelatedTags = null;
        foreach ($relatedTags as $relatedTag) {
            $this->addRelatedTag($relatedTag);
        }

        $this->collRelatedTags = $relatedTags;
        $this->collRelatedTagsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related RelatedTag objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related RelatedTag objects.
     * @throws PropelException
     */
    public function countRelatedTags(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collRelatedTagsPartial && !$this->isNew();
        if (null === $this->collRelatedTags || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collRelatedTags) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getRelatedTags());
            }

            $query = ChildRelatedTagQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDuty($this)
                ->count($con);
        }

        return count($this->collRelatedTags);
    }

    /**
     * Method called to associate a ChildRelatedTag object to this object
     * through the ChildRelatedTag foreign key attribute.
     *
     * @param  ChildRelatedTag $l ChildRelatedTag
     * @return $this|\Perfumerlabs\Start\Model\Duty The current object (for fluent API support)
     */
    public function addRelatedTag(ChildRelatedTag $l)
    {
        if ($this->collRelatedTags === null) {
            $this->initRelatedTags();
            $this->collRelatedTagsPartial = true;
        }

        if (!$this->collRelatedTags->contains($l)) {
            $this->doAddRelatedTag($l);

            if ($this->relatedTagsScheduledForDeletion and $this->relatedTagsScheduledForDeletion->contains($l)) {
                $this->relatedTagsScheduledForDeletion->remove($this->relatedTagsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildRelatedTag $relatedTag The ChildRelatedTag object to add.
     */
    protected function doAddRelatedTag(ChildRelatedTag $relatedTag)
    {
        $this->collRelatedTags[]= $relatedTag;
        $relatedTag->setDuty($this);
    }

    /**
     * @param  ChildRelatedTag $relatedTag The ChildRelatedTag object to remove.
     * @return $this|ChildDuty The current object (for fluent API support)
     */
    public function removeRelatedTag(ChildRelatedTag $relatedTag)
    {
        if ($this->getRelatedTags()->contains($relatedTag)) {
            $pos = $this->collRelatedTags->search($relatedTag);
            $this->collRelatedTags->remove($pos);
            if (null === $this->relatedTagsScheduledForDeletion) {
                $this->relatedTagsScheduledForDeletion = clone $this->collRelatedTags;
                $this->relatedTagsScheduledForDeletion->clear();
            }
            $this->relatedTagsScheduledForDeletion[]= clone $relatedTag;
            $relatedTag->setDuty(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Duty is new, it will return
     * an empty collection; or if this Duty has previously
     * been saved, it will retrieve related RelatedTags from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Duty.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildRelatedTag[] List of ChildRelatedTag objects
     */
    public function getRelatedTagsJoinTag(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildRelatedTagQuery::create(null, $criteria);
        $query->joinWith('Tag', $joinBehavior);

        return $this->getRelatedTags($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aActivity) {
            $this->aActivity->removeDuty($this);
        }
        if (null !== $this->aUser) {
            $this->aUser->removeDuty($this);
        }
        $this->id = null;
        $this->user_id = null;
        $this->activity_id = null;
        $this->comment = null;
        $this->raised_at = null;
        $this->picked_at = null;
        $this->closed_at = null;
        $this->validation_url = null;
        $this->iframe_url = null;
        $this->description = null;
        $this->code = null;
        $this->created_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collRelatedTags) {
                foreach ($this->collRelatedTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collRelatedTags = null;
        $this->aActivity = null;
        $this->aUser = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(DutyTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
