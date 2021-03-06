<?php

namespace Perfumerlabs\Start\Model\Base;

use \Exception;
use \PDO;
use Perfumerlabs\Start\Model\Activity as ChildActivity;
use Perfumerlabs\Start\Model\ActivityQuery as ChildActivityQuery;
use Perfumerlabs\Start\Model\Map\ActivityTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'activity' table.
 *
 *
 *
 * @method     ChildActivityQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildActivityQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildActivityQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method     ChildActivityQuery orderByIframeUrl($order = Criteria::ASC) Order by the iframe_url column
 * @method     ChildActivityQuery orderByClosing($order = Criteria::ASC) Order by the closing column
 * @method     ChildActivityQuery orderByCommenting($order = Criteria::ASC) Order by the commenting column
 * @method     ChildActivityQuery orderByPostponing($order = Criteria::ASC) Order by the postponing column
 * @method     ChildActivityQuery orderByColor($order = Criteria::ASC) Order by the color column
 * @method     ChildActivityQuery orderByPriority($order = Criteria::ASC) Order by the priority column
 * @method     ChildActivityQuery orderByVendorId($order = Criteria::ASC) Order by the vendor_id column
 *
 * @method     ChildActivityQuery groupById() Group by the id column
 * @method     ChildActivityQuery groupByName() Group by the name column
 * @method     ChildActivityQuery groupByCode() Group by the code column
 * @method     ChildActivityQuery groupByIframeUrl() Group by the iframe_url column
 * @method     ChildActivityQuery groupByClosing() Group by the closing column
 * @method     ChildActivityQuery groupByCommenting() Group by the commenting column
 * @method     ChildActivityQuery groupByPostponing() Group by the postponing column
 * @method     ChildActivityQuery groupByColor() Group by the color column
 * @method     ChildActivityQuery groupByPriority() Group by the priority column
 * @method     ChildActivityQuery groupByVendorId() Group by the vendor_id column
 *
 * @method     ChildActivityQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildActivityQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildActivityQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildActivityQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildActivityQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildActivityQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildActivityQuery leftJoinVendor($relationAlias = null) Adds a LEFT JOIN clause to the query using the Vendor relation
 * @method     ChildActivityQuery rightJoinVendor($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Vendor relation
 * @method     ChildActivityQuery innerJoinVendor($relationAlias = null) Adds a INNER JOIN clause to the query using the Vendor relation
 *
 * @method     ChildActivityQuery joinWithVendor($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Vendor relation
 *
 * @method     ChildActivityQuery leftJoinWithVendor() Adds a LEFT JOIN clause and with to the query using the Vendor relation
 * @method     ChildActivityQuery rightJoinWithVendor() Adds a RIGHT JOIN clause and with to the query using the Vendor relation
 * @method     ChildActivityQuery innerJoinWithVendor() Adds a INNER JOIN clause and with to the query using the Vendor relation
 *
 * @method     ChildActivityQuery leftJoinDuty($relationAlias = null) Adds a LEFT JOIN clause to the query using the Duty relation
 * @method     ChildActivityQuery rightJoinDuty($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Duty relation
 * @method     ChildActivityQuery innerJoinDuty($relationAlias = null) Adds a INNER JOIN clause to the query using the Duty relation
 *
 * @method     ChildActivityQuery joinWithDuty($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Duty relation
 *
 * @method     ChildActivityQuery leftJoinWithDuty() Adds a LEFT JOIN clause and with to the query using the Duty relation
 * @method     ChildActivityQuery rightJoinWithDuty() Adds a RIGHT JOIN clause and with to the query using the Duty relation
 * @method     ChildActivityQuery innerJoinWithDuty() Adds a INNER JOIN clause and with to the query using the Duty relation
 *
 * @method     ChildActivityQuery leftJoinNav($relationAlias = null) Adds a LEFT JOIN clause to the query using the Nav relation
 * @method     ChildActivityQuery rightJoinNav($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Nav relation
 * @method     ChildActivityQuery innerJoinNav($relationAlias = null) Adds a INNER JOIN clause to the query using the Nav relation
 *
 * @method     ChildActivityQuery joinWithNav($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Nav relation
 *
 * @method     ChildActivityQuery leftJoinWithNav() Adds a LEFT JOIN clause and with to the query using the Nav relation
 * @method     ChildActivityQuery rightJoinWithNav() Adds a RIGHT JOIN clause and with to the query using the Nav relation
 * @method     ChildActivityQuery innerJoinWithNav() Adds a INNER JOIN clause and with to the query using the Nav relation
 *
 * @method     ChildActivityQuery leftJoinActivityAccess($relationAlias = null) Adds a LEFT JOIN clause to the query using the ActivityAccess relation
 * @method     ChildActivityQuery rightJoinActivityAccess($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ActivityAccess relation
 * @method     ChildActivityQuery innerJoinActivityAccess($relationAlias = null) Adds a INNER JOIN clause to the query using the ActivityAccess relation
 *
 * @method     ChildActivityQuery joinWithActivityAccess($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ActivityAccess relation
 *
 * @method     ChildActivityQuery leftJoinWithActivityAccess() Adds a LEFT JOIN clause and with to the query using the ActivityAccess relation
 * @method     ChildActivityQuery rightJoinWithActivityAccess() Adds a RIGHT JOIN clause and with to the query using the ActivityAccess relation
 * @method     ChildActivityQuery innerJoinWithActivityAccess() Adds a INNER JOIN clause and with to the query using the ActivityAccess relation
 *
 * @method     \Perfumerlabs\Start\Model\VendorQuery|\Perfumerlabs\Start\Model\DutyQuery|\Perfumerlabs\Start\Model\NavQuery|\Perfumerlabs\Start\Model\ActivityAccessQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildActivity findOne(ConnectionInterface $con = null) Return the first ChildActivity matching the query
 * @method     ChildActivity findOneOrCreate(ConnectionInterface $con = null) Return the first ChildActivity matching the query, or a new ChildActivity object populated from the query conditions when no match is found
 *
 * @method     ChildActivity findOneById(int $id) Return the first ChildActivity filtered by the id column
 * @method     ChildActivity findOneByName(string $name) Return the first ChildActivity filtered by the name column
 * @method     ChildActivity findOneByCode(string $code) Return the first ChildActivity filtered by the code column
 * @method     ChildActivity findOneByIframeUrl(string $iframe_url) Return the first ChildActivity filtered by the iframe_url column
 * @method     ChildActivity findOneByClosing(boolean $closing) Return the first ChildActivity filtered by the closing column
 * @method     ChildActivity findOneByCommenting(boolean $commenting) Return the first ChildActivity filtered by the commenting column
 * @method     ChildActivity findOneByPostponing(boolean $postponing) Return the first ChildActivity filtered by the postponing column
 * @method     ChildActivity findOneByColor(string $color) Return the first ChildActivity filtered by the color column
 * @method     ChildActivity findOneByPriority(int $priority) Return the first ChildActivity filtered by the priority column
 * @method     ChildActivity findOneByVendorId(int $vendor_id) Return the first ChildActivity filtered by the vendor_id column *

 * @method     ChildActivity requirePk($key, ConnectionInterface $con = null) Return the ChildActivity by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActivity requireOne(ConnectionInterface $con = null) Return the first ChildActivity matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildActivity requireOneById(int $id) Return the first ChildActivity filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActivity requireOneByName(string $name) Return the first ChildActivity filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActivity requireOneByCode(string $code) Return the first ChildActivity filtered by the code column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActivity requireOneByIframeUrl(string $iframe_url) Return the first ChildActivity filtered by the iframe_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActivity requireOneByClosing(boolean $closing) Return the first ChildActivity filtered by the closing column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActivity requireOneByCommenting(boolean $commenting) Return the first ChildActivity filtered by the commenting column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActivity requireOneByPostponing(boolean $postponing) Return the first ChildActivity filtered by the postponing column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActivity requireOneByColor(string $color) Return the first ChildActivity filtered by the color column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActivity requireOneByPriority(int $priority) Return the first ChildActivity filtered by the priority column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildActivity requireOneByVendorId(int $vendor_id) Return the first ChildActivity filtered by the vendor_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildActivity[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildActivity objects based on current ModelCriteria
 * @method     ChildActivity[]|ObjectCollection findById(int $id) Return ChildActivity objects filtered by the id column
 * @method     ChildActivity[]|ObjectCollection findByName(string $name) Return ChildActivity objects filtered by the name column
 * @method     ChildActivity[]|ObjectCollection findByCode(string $code) Return ChildActivity objects filtered by the code column
 * @method     ChildActivity[]|ObjectCollection findByIframeUrl(string $iframe_url) Return ChildActivity objects filtered by the iframe_url column
 * @method     ChildActivity[]|ObjectCollection findByClosing(boolean $closing) Return ChildActivity objects filtered by the closing column
 * @method     ChildActivity[]|ObjectCollection findByCommenting(boolean $commenting) Return ChildActivity objects filtered by the commenting column
 * @method     ChildActivity[]|ObjectCollection findByPostponing(boolean $postponing) Return ChildActivity objects filtered by the postponing column
 * @method     ChildActivity[]|ObjectCollection findByColor(string $color) Return ChildActivity objects filtered by the color column
 * @method     ChildActivity[]|ObjectCollection findByPriority(int $priority) Return ChildActivity objects filtered by the priority column
 * @method     ChildActivity[]|ObjectCollection findByVendorId(int $vendor_id) Return ChildActivity objects filtered by the vendor_id column
 * @method     ChildActivity[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ActivityQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Perfumerlabs\Start\Model\Base\ActivityQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'start', $modelName = '\\Perfumerlabs\\Start\\Model\\Activity', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildActivityQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildActivityQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildActivityQuery) {
            return $criteria;
        }
        $query = new ChildActivityQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildActivity|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ActivityTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ActivityTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildActivity A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, code, iframe_url, closing, commenting, postponing, color, priority, vendor_id FROM activity WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildActivity $obj */
            $obj = new ChildActivity();
            $obj->hydrate($row);
            ActivityTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildActivity|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ActivityTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ActivityTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ActivityTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ActivityTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE code = 'fooValue'
     * $query->filterByCode('%fooValue%', Criteria::LIKE); // WHERE code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $code The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityTableMap::COL_CODE, $code, $comparison);
    }

    /**
     * Filter the query on the iframe_url column
     *
     * Example usage:
     * <code>
     * $query->filterByIframeUrl('fooValue');   // WHERE iframe_url = 'fooValue'
     * $query->filterByIframeUrl('%fooValue%', Criteria::LIKE); // WHERE iframe_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $iframeUrl The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterByIframeUrl($iframeUrl = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($iframeUrl)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityTableMap::COL_IFRAME_URL, $iframeUrl, $comparison);
    }

    /**
     * Filter the query on the closing column
     *
     * Example usage:
     * <code>
     * $query->filterByClosing(true); // WHERE closing = true
     * $query->filterByClosing('yes'); // WHERE closing = true
     * </code>
     *
     * @param     boolean|string $closing The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterByClosing($closing = null, $comparison = null)
    {
        if (is_string($closing)) {
            $closing = in_array(strtolower($closing), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ActivityTableMap::COL_CLOSING, $closing, $comparison);
    }

    /**
     * Filter the query on the commenting column
     *
     * Example usage:
     * <code>
     * $query->filterByCommenting(true); // WHERE commenting = true
     * $query->filterByCommenting('yes'); // WHERE commenting = true
     * </code>
     *
     * @param     boolean|string $commenting The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterByCommenting($commenting = null, $comparison = null)
    {
        if (is_string($commenting)) {
            $commenting = in_array(strtolower($commenting), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ActivityTableMap::COL_COMMENTING, $commenting, $comparison);
    }

    /**
     * Filter the query on the postponing column
     *
     * Example usage:
     * <code>
     * $query->filterByPostponing(true); // WHERE postponing = true
     * $query->filterByPostponing('yes'); // WHERE postponing = true
     * </code>
     *
     * @param     boolean|string $postponing The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterByPostponing($postponing = null, $comparison = null)
    {
        if (is_string($postponing)) {
            $postponing = in_array(strtolower($postponing), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ActivityTableMap::COL_POSTPONING, $postponing, $comparison);
    }

    /**
     * Filter the query on the color column
     *
     * Example usage:
     * <code>
     * $query->filterByColor('fooValue');   // WHERE color = 'fooValue'
     * $query->filterByColor('%fooValue%', Criteria::LIKE); // WHERE color LIKE '%fooValue%'
     * </code>
     *
     * @param     string $color The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterByColor($color = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($color)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityTableMap::COL_COLOR, $color, $comparison);
    }

    /**
     * Filter the query on the priority column
     *
     * Example usage:
     * <code>
     * $query->filterByPriority(1234); // WHERE priority = 1234
     * $query->filterByPriority(array(12, 34)); // WHERE priority IN (12, 34)
     * $query->filterByPriority(array('min' => 12)); // WHERE priority > 12
     * </code>
     *
     * @param     mixed $priority The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterByPriority($priority = null, $comparison = null)
    {
        if (is_array($priority)) {
            $useMinMax = false;
            if (isset($priority['min'])) {
                $this->addUsingAlias(ActivityTableMap::COL_PRIORITY, $priority['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($priority['max'])) {
                $this->addUsingAlias(ActivityTableMap::COL_PRIORITY, $priority['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityTableMap::COL_PRIORITY, $priority, $comparison);
    }

    /**
     * Filter the query on the vendor_id column
     *
     * Example usage:
     * <code>
     * $query->filterByVendorId(1234); // WHERE vendor_id = 1234
     * $query->filterByVendorId(array(12, 34)); // WHERE vendor_id IN (12, 34)
     * $query->filterByVendorId(array('min' => 12)); // WHERE vendor_id > 12
     * </code>
     *
     * @see       filterByVendor()
     *
     * @param     mixed $vendorId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function filterByVendorId($vendorId = null, $comparison = null)
    {
        if (is_array($vendorId)) {
            $useMinMax = false;
            if (isset($vendorId['min'])) {
                $this->addUsingAlias(ActivityTableMap::COL_VENDOR_ID, $vendorId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($vendorId['max'])) {
                $this->addUsingAlias(ActivityTableMap::COL_VENDOR_ID, $vendorId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ActivityTableMap::COL_VENDOR_ID, $vendorId, $comparison);
    }

    /**
     * Filter the query by a related \Perfumerlabs\Start\Model\Vendor object
     *
     * @param \Perfumerlabs\Start\Model\Vendor|ObjectCollection $vendor The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildActivityQuery The current query, for fluid interface
     */
    public function filterByVendor($vendor, $comparison = null)
    {
        if ($vendor instanceof \Perfumerlabs\Start\Model\Vendor) {
            return $this
                ->addUsingAlias(ActivityTableMap::COL_VENDOR_ID, $vendor->getId(), $comparison);
        } elseif ($vendor instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ActivityTableMap::COL_VENDOR_ID, $vendor->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByVendor() only accepts arguments of type \Perfumerlabs\Start\Model\Vendor or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Vendor relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function joinVendor($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Vendor');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Vendor');
        }

        return $this;
    }

    /**
     * Use the Vendor relation Vendor object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Perfumerlabs\Start\Model\VendorQuery A secondary query class using the current class as primary query
     */
    public function useVendorQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinVendor($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Vendor', '\Perfumerlabs\Start\Model\VendorQuery');
    }

    /**
     * Filter the query by a related \Perfumerlabs\Start\Model\Duty object
     *
     * @param \Perfumerlabs\Start\Model\Duty|ObjectCollection $duty the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildActivityQuery The current query, for fluid interface
     */
    public function filterByDuty($duty, $comparison = null)
    {
        if ($duty instanceof \Perfumerlabs\Start\Model\Duty) {
            return $this
                ->addUsingAlias(ActivityTableMap::COL_ID, $duty->getActivityId(), $comparison);
        } elseif ($duty instanceof ObjectCollection) {
            return $this
                ->useDutyQuery()
                ->filterByPrimaryKeys($duty->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDuty() only accepts arguments of type \Perfumerlabs\Start\Model\Duty or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Duty relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function joinDuty($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Duty');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Duty');
        }

        return $this;
    }

    /**
     * Use the Duty relation Duty object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Perfumerlabs\Start\Model\DutyQuery A secondary query class using the current class as primary query
     */
    public function useDutyQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDuty($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Duty', '\Perfumerlabs\Start\Model\DutyQuery');
    }

    /**
     * Filter the query by a related \Perfumerlabs\Start\Model\Nav object
     *
     * @param \Perfumerlabs\Start\Model\Nav|ObjectCollection $nav the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildActivityQuery The current query, for fluid interface
     */
    public function filterByNav($nav, $comparison = null)
    {
        if ($nav instanceof \Perfumerlabs\Start\Model\Nav) {
            return $this
                ->addUsingAlias(ActivityTableMap::COL_ID, $nav->getActivityId(), $comparison);
        } elseif ($nav instanceof ObjectCollection) {
            return $this
                ->useNavQuery()
                ->filterByPrimaryKeys($nav->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByNav() only accepts arguments of type \Perfumerlabs\Start\Model\Nav or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Nav relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function joinNav($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Nav');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Nav');
        }

        return $this;
    }

    /**
     * Use the Nav relation Nav object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Perfumerlabs\Start\Model\NavQuery A secondary query class using the current class as primary query
     */
    public function useNavQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinNav($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Nav', '\Perfumerlabs\Start\Model\NavQuery');
    }

    /**
     * Filter the query by a related \Perfumerlabs\Start\Model\ActivityAccess object
     *
     * @param \Perfumerlabs\Start\Model\ActivityAccess|ObjectCollection $activityAccess the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildActivityQuery The current query, for fluid interface
     */
    public function filterByActivityAccess($activityAccess, $comparison = null)
    {
        if ($activityAccess instanceof \Perfumerlabs\Start\Model\ActivityAccess) {
            return $this
                ->addUsingAlias(ActivityTableMap::COL_ID, $activityAccess->getActivityId(), $comparison);
        } elseif ($activityAccess instanceof ObjectCollection) {
            return $this
                ->useActivityAccessQuery()
                ->filterByPrimaryKeys($activityAccess->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByActivityAccess() only accepts arguments of type \Perfumerlabs\Start\Model\ActivityAccess or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ActivityAccess relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function joinActivityAccess($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ActivityAccess');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ActivityAccess');
        }

        return $this;
    }

    /**
     * Use the ActivityAccess relation ActivityAccess object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Perfumerlabs\Start\Model\ActivityAccessQuery A secondary query class using the current class as primary query
     */
    public function useActivityAccessQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinActivityAccess($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ActivityAccess', '\Perfumerlabs\Start\Model\ActivityAccessQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildActivity $activity Object to remove from the list of results
     *
     * @return $this|ChildActivityQuery The current query, for fluid interface
     */
    public function prune($activity = null)
    {
        if ($activity) {
            $this->addUsingAlias(ActivityTableMap::COL_ID, $activity->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the activity table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ActivityTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ActivityTableMap::clearInstancePool();
            ActivityTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ActivityTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ActivityTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ActivityTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ActivityTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ActivityQuery
