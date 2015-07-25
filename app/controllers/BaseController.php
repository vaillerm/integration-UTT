<?php

/**
 * Base controller, to be used by every controller.
 *
 * It implements basic wrappers for responses, ...
 *
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	/**
	 * Success response wrapper.
	 *
	 * @param  string $message
	 * @param  array  $data
	 * @return array
	 */
	public function success($message = '', $data = [])
	{
		if (Request::ajax())
		{
			return ['status' => 'success', 'message' => $message, 'data' => $data];
		}
		return Redirect::back()->withSuccess($message);
	}

	/**
	 * Error response wrapper.
	 *
	 * @param  string $message
	 * @return array
	 */
	public function error($message = '')
	{
		if (Request::ajax())
		{
			return ['status' => 'error', 'message' => $message];
		}
		return Redirect::back()->withError($message);
	}

}
