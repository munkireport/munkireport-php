<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Compatibility\Service\Tablequery;

class DatatablesController extends Controller
{
    /**
     * @OA\Post(
     *     path="/datatables/data",
     *     summary="Get data",
     *     description="Generate a list of results based on a number of different query parameters",
     *     tags={"v5"},
     *     @OA\RequestBody(
     *       required=true,
     *       request="datatablesRequest",
     *       description="Use this API to construct a generic query and get a list of records back as json formatted data",
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              schema="Parameters",
     *              type="object",
     *
     *              @OA\Property(
     *                  property="columns",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                          description="column name as table.columnname",
     *                      ),
     *                      @OA\Property(
     *                          property="search",
     *                          type="object",
     *                          @OA\Property(
     *                              property="value",
     *                              type="string",
     *                          ),
     *                      ),
     *                  )
     *              ),
     *
     *              @OA\Property(
     *                  property="order",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="column",
     *                          type="string",
     *                          description="column name as table.columnname to sort by",
     *                      ),
     *                      @OA\Property(
     *                          property="dir",
     *                          type="string",
     *                          description="sort direction for this column, ASC or DESC",
     *                      ),
     *                  ),
     *              ),
     *
     *              @OA\Property(
     *                  property="start",
     *                  type="integer",
     *                  default=0,
     *                  description="The row offset to start fetching from. Use in conjunction with length to perform paging",
     *              ),
     *
     *              @OA\Property(
     *                  property="length",
     *                  type="integer",
     *                  default=-1,
     *                  description="The number of records to return, or -1 for all records",
     *              ),
     *
     *              @OA\Property(
     *                  property="draw",
     *                  type="integer",
     *                  default=0,
     *                  description="Does not affect the query output at all",
     *              ),
     *
     *              @OA\Property(
     *                  property="search",
     *                  type="object",
     *                  @OA\Property(
     *                      property="value",
     *                      type="string",
     *                      description="a string value to match against for all columns in the query",
     *                  ),
     *              ),
     *
     *              @OA\Property(
     *                  property="search_cols",
     *                  type="array",
     *                  description="apply search conditions to specific columns. overrides the search value which is discarded if this is present",
     *                  @OA\Items(type="string")
     *              ),
     *
     *              @OA\Property(
     *                  property="where",
     *                  type="array",
     *                  description="additional WHERE clause that would not be covered by search or paging. Multiple items are ANDed",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(
     *                          property="operator",
     *                          type="string",
     *                          default="=",
     *                          description="WHERE clause operator",
     *                      ),
     *                      @OA\Property(
     *                          property="table",
     *                          type="string",
     *                          description="The table to apply the WHERE clause to",
     *                      ),
     *                      @OA\Property(
     *                          property="column",
     *                          type="string",
     *                          description="The column to apply the WHERE clause to",
     *                      ),
     *                      @OA\Property(
     *                          property="value",
     *                          type="string",
     *                          description="The value (aka the operand) of the WHERE clause",
     *                      ),
     *                  )
     *              ),
     *
     *              @OA\Property(
     *                  property="mrColNotEmpty",
     *                  type="string",
     *                  description="specify a single column where results returned cannot contain a NULL value",
     *              ),
     *              @OA\Property(
     *                  property="mrColNotEmptyBlank",
     *                  type="string",
     *                  description="specify a single column where results returned cannot contain an empty string value",
     *              ),
     *
     *          )
     *       )
     *     ),
     *     @OA\Response(
     *       response="200",
     *       description="successful operation",
     *       @OA\JsonContent(
     *          @OA\Property(
     *              property="data",
     *              type="array",
     *              description="results returned from query",
     *              @OA\Items(type="object")
     *          ),
     *          @OA\Property(
     *              property="draw",
     *              type="integer",
     *              description="internal use",
     *          ),
     *          @OA\Property(
     *              property="recordsTotal",
     *              type="integer",
     *              description="Total number of records that would have been returned by this query",
     *          ),
     *          @OA\Property(
     *              property="recordsFiltered",
     *              type="integer",
     *              description="Total number of records that would have been returned without search terms",
     *          ),
     *       )
     *     )
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        // Sanitize the GET variables here.
        $cfg = array(
            'columns' => $request->input('columns', array()),
            'order' => $request->input('order', array()),
            'start' => $request->input('start', 0), // Start
            'length' => $request->input('length', -1), // Length
            'draw' => $request->input('draw', 0), // Identifier, just return
            'search' => $request->input('search.value', ''), // Search query
            'search_cols' => [],
            'where' => $request->input('where', ''), // Optional where clause
            'mrColNotEmpty' => $request->input('mrColNotEmpty', ''), // Munkireport non empty column name
            'mrColNotEmptyBlank' => $request->input('mrColNotEmptyBlank', '') // Munkireport non empty column name
        );

        try {
            // Get model
            $obj = new Tablequery();
            return response()->json($obj->fetch($cfg));
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'draw' => intval($cfg['draw'])
            ]);
        }
    }
}
